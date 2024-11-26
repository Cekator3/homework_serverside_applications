<?php

namespace App\Utils;

/**
 * Модуль для хранения сообщений и ошибок
 */
class ErrorMessages
{
    private array $errors = [];
    private array $messages = [];

    /**
     * Добавляет новое сообщение
     *
     * @param string $message Текст сообщения
     * @param string $isError Является ли это сообщение об ошибке
     */
    public function add(string $message, bool $isError) : void
    {
        if ($isError)
            $this->errors []= $message;
        else
            $this->messages []= $message;
    }

    /**
     * Возвращает все собранные сообщения
     */
    public function getAll() : array
    {
        return $this->messages;
    }

    public function getErrors() : array
    {
        return $this->errors;
    }

    /**
     * Проверяет, было ли добавлено хотя бы одно сообщение об ошибке
     */
    public function hasErrors() : bool
    {
        return !empty($this->errors);
    }

    public function clear(): void
    {
        $this->errors = [];
        $this->messages = [];
    }
}
