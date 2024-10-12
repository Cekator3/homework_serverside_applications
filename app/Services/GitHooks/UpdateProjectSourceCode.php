<?php

namespace App\Services\GitHooks;


use Log;
use Exception;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Process;
use Illuminate\Process\Exceptions\ProcessFailedException;
use Illuminate\Process\Exceptions\ProcessTimedOutException;
use App\Services\GitHooks\Exceptions\InvalidSecretKeyException;
use App\Services\GitHooks\Exceptions\UpdateProjectSourceCodeException;
use App\Services\GitHooks\Exceptions\AlreadyUpdatingSourceCodeException;
use App\Services\GitHooks\Exceptions\FailedToUpdateProjectSourceCodeException;
use App\Services\GitHooks\Exceptions\SecretKeyEnvironmentVariableNotSetException;

/**
 * Модуль для обновления исходного кода проекта
 */
class UpdateProjectSourceCode
{
    private static function log(string $message, mixed $userInfo): void
    {
        Log::info($message, [
            'date' => now(),
            'user_info' => $userInfo
        ]);
    }

    private static function runGitCommand(array $command) : void
    {
        try
        {
            Process::path(__DIR__)->run($command)->throw();
        }
        catch (ProcessFailedException | ProcessTimedOutException $e)
        {
            throw new FailedToUpdateProjectSourceCodeException("Failed to update project source code. Got: ". $e->getMessage());
        }
    }

    private static function updateProjectSourceCode(mixed $userInfo): void
    {
        static::log("Updating project source code...", $userInfo);

        static::log('Switching to main branch...', $userInfo);
        static::runGitCommand(['git', 'checkout', 'main']);

        static::log('Discarding all changes...', $userInfo);
        static::runGitCommand(['git', 'reset', '--hard']);

        static::log('Pulling latest changes...', $userInfo);
        static::runGitCommand(['git', 'pull', 'origin', 'main']);

        static::log("Project source code updated successfully!", $userInfo);
    }

    /**
     * Обновляет исходный код проекта
     *
     * @param $secretKey Секретный ключ для авторизации этого действия
     * @param $userInfo Информация о пользователе, производящем это действие
     * @throws UpdateProjectSourceCodeException
     */
    public static function run(string $secretKey, mixed $userInfo)
    {
        if (config('githook.secret_key') === null)
            throw new SecretKeyEnvironmentVariableNotSetException();

        if ($secretKey !== config('githook.secret_key'))
            throw new InvalidSecretKeyException();

        $lock = Cache::lock('update_source_code_lock');
        if (! $lock->get())
            throw new AlreadyUpdatingSourceCodeException();

        try
        {
            static::updateProjectSourceCode($userInfo);
            $lock->release();
        }
        catch(Exception $e)
        {
            $lock->release();
            throw $e;
        }
    }
}
