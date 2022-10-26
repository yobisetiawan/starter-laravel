<?php

namespace App\Doc\V1\Auth;

/**

 * @OA\Post(
 * path="/auth/verify-email",
 * tags={"Auth"},
 * @OA\RequestBody( @OA\MediaType( mediaType="application/x-www-form-urlencoded", @OA\Schema(
 *      @OA\Property(
 *          property="email",
 *      ),
 *      @OA\Property(
 *          property="code",
 *      ),
 *      @OA\Property(
 *          property="action",
 *          enum={"resend", "verify"}
 *      )
 * ))),
 * @OA\Response(response=200, description="return success true", @OA\JsonContent()),
 * )
 */

class VerifyEmailController
{
}
