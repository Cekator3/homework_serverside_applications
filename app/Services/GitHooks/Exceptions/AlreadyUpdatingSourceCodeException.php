<?php

namespace App\Services\GitHooks\Exceptions;

use App\Services\GitHooks\Exceptions\UpdateProjectSourceCodeException;

class AlreadyUpdatingSourceCodeException extends UpdateProjectSourceCodeException
{
    public function __construct()
    {
        parent::__construct("The project source code is already being updated by another user");
    }
}
