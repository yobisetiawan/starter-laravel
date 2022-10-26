<?php

namespace App\Doc\V1\Auth;

/**
 *
 * @OA\Post(
 * path="/auth/login",
 * tags={"Auth"},
 * @OA\RequestBody( @OA\MediaType( mediaType="application/x-www-form-urlencoded", @OA\Schema(
 *      @OA\Property(
 *          property="email",
 *      ),
 *      @OA\Property(
 *          property="password",
 *      )
 * ))),
 * @OA\Response(response=200, description="return token", @OA\JsonContent()),
 * )
 *
 * @OA\Post(
 * path="/auth/logout",
 * tags={"Auth"},
 * security={{"bearerAuth":{}}},
 * @OA\Response(response=200, description="return success true", @OA\JsonContent()),
 * )
 */

class LoginController
{
}
