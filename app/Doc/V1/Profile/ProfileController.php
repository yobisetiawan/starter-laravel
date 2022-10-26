<?php

namespace App\Doc\V1\Profile;

/**

 *
 * @OA\Get(
 * path="/user",
 * tags={"User - Profile"},
 * @OA\Parameter(ref="#/components/parameters/OA_Relations"),
 * security={{"bearerAuth":{}}},
 * @OA\Response(response=200, description="return User model", @OA\JsonContent()),
 * )

 *
 * @OA\Delete(
 * path="/user",
 * tags={"User - Profile"},
 * security={{"bearerAuth":{}}},
 * @OA\Response(response=200, description="return Success True", @OA\JsonContent()),
 * )
 */

class ProfileController
{
}
