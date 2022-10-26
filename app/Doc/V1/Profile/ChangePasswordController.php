<?php

namespace App\Doc\V1\Profile;

/**
 *
 * @OA\Post(
 * path="/user/change-password",
 * tags={"User - Profile"},
  * @OA\RequestBody( @OA\MediaType( mediaType="application/x-www-form-urlencoded", @OA\Schema(
 *      @OA\Property(
 *          property="old_password",
 *      ),
 *      @OA\Property(
 *          property="password",
 *      ),
 *      @OA\Property(
 *          property="password_confirmation",
 *      )
 * ))),
 * @OA\Parameter(ref="#/components/parameters/OA_Relations"),
 * security={{"bearerAuth":{}}},
 * @OA\Response(response=200, description="return User model", @OA\JsonContent()),
 * )
 */

class ChangePasswordController
{
}
