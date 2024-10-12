<?php

namespace App\Services\GitHooks\Exceptions;

use App\Services\GitHooks\Exceptions\UpdateProjectSourceCodeException;

class InvalidSecretKeyException extends UpdateProjectSourceCodeException
{
    public function __construct()
    {
        parent::__construct("Invalid secret key");
    }
}
