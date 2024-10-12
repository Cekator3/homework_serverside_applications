<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use App\Services\GitHooks\UpdateProjectSourceCode;
use App\Services\GitHooks\Exceptions\InvalidSecretKeyException;
use App\Services\GitHooks\Exceptions\AlreadyUpdatingSourceCodeException;
use App\Services\GitHooks\Exceptions\FailedToUpdateProjectSourceCodeException;
use App\Services\GitHooks\Exceptions\SecretKeyEnvironmentVariableNotSetException;

class GitHooksController
{
    function updateProjectSourceCode(Request $request)
    {
        try
        {
            $secretKey = $request->input('secret_key');
            UpdateProjectSourceCode::run($secretKey, $request->ip());
            return new JsonResponse("Project source code updated successfully!");
        }
        catch (SecretKeyEnvironmentVariableNotSetException | FailedToUpdateProjectSourceCodeException $e)
        {
            return new JsonResponse($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        catch (InvalidSecretKeyException | AlreadyUpdatingSourceCodeException $e)
        {
            return new JsonResponse($e->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }
}
