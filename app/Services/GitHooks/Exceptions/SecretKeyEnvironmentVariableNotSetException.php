<?php

namespace App\Services\GitHooks\Exceptions;

use App\Services\GitHooks\Exceptions\UpdateProjectSourceCodeException;

class SecretKeyEnvironmentVariableNotSetException extends UpdateProjectSourceCodeException
{
    public function __construct()
    {
        parent::__construct("GITHOOK_SECRET_KEY environment variable isn't set");
    }
}
