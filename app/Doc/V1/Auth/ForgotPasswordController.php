<?php

namespace App\Doc\V1\Auth;

/**
 *
 * @OA\Post(
 * path="/auth/forgot-password",
 * tags={"Auth"},
 * @OA\RequestBody( @OA\MediaType( mediaType="application/x-www-form-urlencoded", @OA\Schema(
 *      @OA\Property(
 *          property="email",
 *      ),
 * ))),
 * @OA\Response(response=200, description="return token", @OA\JsonContent()),
 * )
 */

class ForgotPasswordController
{
}
