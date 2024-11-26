<?php

namespace App\Imports\Parsers;

use App\Utils\ErrorMessages;
use Illuminate\Http\UploadedFile;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Validators\Failure;
use App\Imports\Parsers\DTOs\ParsedEntity;
use App\Imports\Exceptions\ValidationException;

/**
 * Модуль для парсинга файла с данными о сущности
 * @throws \App\Imports\Exceptions\ValidationException
 */
abstract class BaseParser
{
    private ErrorMessages $messages;

    public function __construct()
    {
        $this->messages = new ErrorMessages();
    }

    /**
     * Ожидаемые названия столбцов
     */
    abstract protected function expectedColumns(): array;

    abstract protected function rules(): array;

    abstract protected function parseBodyRow(array $row, int $rowNumber): ParsedEntity;

    public function getErrors() : array
    {
        return $this->messages->getErrors();
    }

    public function hasErrors() : bool
    {
        return $this->messages->hasErrors();
    }

    private function validateBodyRow(array $row, int $rowNumber): void
    {
        $rules = $this->rules();

        $columns = array_combine($this->expectedColumns(), $row);
        $validator = \Validator::make($columns, $rules);

        if (! $validator->fails())
            return;

        $errors = $validator->errors()->all();
        $this->onFailure($rowNumber, implode(", ", $errors));
    }

    /**
     * Ensures that the header row has correct columns order.
     *
     * @param array $headerRow
     * @return void
     */
    private function ensureHeaderColumnsInCorrectOrder(array $headerRow): void
    {
        $expectedColumns = $this->expectedColumns();
        for ($i=0; $i < count($expectedColumns); $i++)
        {
            if ($expectedColumns[$i] == $headerRow[$i])
                continue;
            $this->onFailure(
                row: 1,
                reason: "Неправильный порядок столбцов. Столбцы должны идти следующим образом: " . implode(", ", $expectedColumns)
            );
            return;
        }
    }

    /**
     * Ensures that the header row contains all expected columns and validates their order.
     *
     * @param array $headerRow
     * @return void
     */
    private function ensureHeaderHasAllExpectedColumns(array $headerRow): void
    {
        $expectedColumns = $this->expectedColumns();
        $missingColumns = array_diff($expectedColumns, $headerRow);
        $extraColumns = array_diff($headerRow, $expectedColumns);

        $errors = [];
        if (!empty($missingColumns))
            $errors []= "Отсутствуют столбцы: " . implode(", ", $missingColumns);
        if (!empty($extraColumns))
            $errors []= "Лишние столбцы: " . implode(", ", $extraColumns);

        if (empty($errors))
            return;

        $this->onFailure(row: 1, reason: implode('. ', $errors));
    }

    /**
     * Удаляет все пустые строки и ячейки
     * @param array $rows
     * @return void
     */
    private function clearRows(array &$rows): void
    {
        foreach ($rows as $rowNumber => &$row)
        {
            foreach ($row as $i => $cell)
            {
                if (empty($cell))
                    unset($row[$i]);
            }
            if (empty($row))
                unset($rows[$rowNumber]);
            else
                $row = array_values($row); // Reindex to have keys 0, 1, 2, ...
        }
        $rows = array_values($rows); // Reindex to have keys 0, 1,
    }

    /**
     * Считывает из файла импортируемые данные
     *
     * @return ParsedEntity[]
     */
    public function parse(UploadedFile $file, bool $ignoreErrors): array
    {
        $data = Excel::toArray([], $file);
        if (empty($data) || empty($data[0]))
        {
            $this->onFailure(row: 0, reason: "Файл пустой");
            return [];
        }
        $rows = $data[0];

        $this->clearRows($rows);

        $this->ensureHeaderHasAllExpectedColumns($rows[0]);
        $this->ensureHeaderColumnsInCorrectOrder($rows[0]);

        if ($this->messages->hasErrors())
            return [];

        $result = [];
        for ($i=1; $i < count($rows); $i++)
        {
            $this->validateBodyRow($rows[$i], rowNumber: $i + 1);
            if ($this->messages->hasErrors())
            {
                if ($ignoreErrors)
                    continue;
                return [];
            }
            $columns = array_combine($this->expectedColumns(), $rows[$i]);
            $result []= $this->parseBodyRow($columns, rowNumber: $i + 1);
        }
        return $result;
    }

    /**
     * Handles validation error
     */
    private function onFailure(int $row, string $reason)
    {
        $err = new ValidationException($row, $reason);
        $this->messages->add($err->getMessage(), isError: true);
    }
}
