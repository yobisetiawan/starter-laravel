<?php

namespace App\Doc\V1\Profile;



/**

 *
 * @OA\Post(
 * path="/user/change-avatar",
 * tags={"User - Profile"},

 * @OA\RequestBody(
 *      required=true,
 *      @OA\MediaType(
 *          mediaType="multipart/form-data",
 *          @OA\Schema(
 *              @OA\Property(
 *                  property="avatar",
 *                  type="string",
 *                  format="binary"
 *              ),
 *          )
 *      )
 * ),
 * @OA\Parameter(ref="#/components/parameters/OA_Relations"),
 * security={{"bearerAuth":{}}},
 * @OA\Response(response=200, description="return User model", @OA\JsonContent()),
 * )
 */


class ChangeAvatarController
{
}

