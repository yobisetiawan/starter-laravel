<?php

namespace App\Doc\V1\Example;

/**
 *
 * @OA\Get(
 * path="/example/samples",
 * tags={"Example"},
 * @OA\Parameter(ref="#/components/parameters/OA_listType"),
 * @OA\Parameter(ref="#/components/parameters/OA_listQ"),
 * @OA\Parameter(ref="#/components/parameters/OA_listPage"),
 * @OA\Parameter(ref="#/components/parameters/OA_SortBy"),
 * @OA\Parameter(ref="#/components/parameters/OA_OrderBy"),
 * @OA\Parameter(ref="#/components/parameters/OA_limit"),
 * @OA\Parameter(ref="#/components/parameters/OA_Relations"),
 * security={{"bearerAuth":{}}},
 * @OA\Response(response=200, description="return Sample Model", @OA\JsonContent()),
 * )
 *
 * @OA\Get(
 * path="/example/samples/{id}",
 * tags={"Example"},
 * @OA\Parameter(ref="#/components/parameters/OA_id"),
 * @OA\Parameter(ref="#/components/parameters/OA_Relations"),
 * security={{"bearerAuth":{}}},
 * @OA\Response(response=200, description="return Sample Model", @OA\JsonContent()),
 * )
 *
 *
 * @OA\Post(
 * path="/example/samples",
 * tags={"Example"},
 * @OA\RequestBody( @OA\MediaType( mediaType="application/x-www-form-urlencoded", @OA\Schema(
 *      @OA\Property(
 *          property="title",
 *      ),
 *      @OA\Property(
 *          property="description",
 *      )
 * ))),
 * @OA\Parameter(ref="#/components/parameters/OA_Relations"),
 * security={{"bearerAuth":{}}},
 * @OA\Response(response=200, description="return Sample Model", @OA\JsonContent()),
 * )
 *
 * @OA\Put(
 * path="/example/samples/{id}",
 * @OA\Parameter(ref="#/components/parameters/OA_id"),
 * tags={"Example"},
 * @OA\RequestBody( @OA\MediaType( mediaType="application/x-www-form-urlencoded", @OA\Schema(
 *      @OA\Property(
 *          property="title",
 *      ),
 *      @OA\Property(
 *          property="description",
 *      )
 * ))),
 * @OA\Parameter(ref="#/components/parameters/OA_Relations"),
 * security={{"bearerAuth":{}}},
 * @OA\Response(response=200, description="return Sample Model", @OA\JsonContent()),
 * )
 *
 * @OA\Delete(
 * path="/example/samples/{id}",
 * tags={"Example"},
 * @OA\Parameter(ref="#/components/parameters/OA_id"),
 * security={{"bearerAuth":{}}},
 * @OA\Response(response=200, description="return Success true", @OA\JsonContent()),
 * )
 *
 */

class SampleController
{
}
