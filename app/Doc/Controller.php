<?php

namespace App\Doc;

/**

 *
 * @OA\OpenApi(
 *   @OA\Server(
 *      url="/api/v1",
 *      description="API LARAVEL V1"
 *   ),
 *   @OA\Info(
 *      title="LARAVEL API",
 *      version="1.0.0",
 *   ),
 * )

 *
 * @OA\SecurityScheme(
 *     type="http",
 *     description="Login with email and password to get the authentication token",
 *     name="Token Based",
 *     in="header",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 *     securityScheme="bearerAuth",
 * )

 *
 * @OA\Parameter(
 *  in="path",
 *  parameter="OA_id",
 *  name="id",
 *  description="Uuid model",
 *  required=true,
 *      @OA\Schema(
 *          type="string"
 *      )
 *  )
 *
 * @OA\Parameter(
 *  in="query",
 *  parameter="OA_listQ",
 *  description="Keyword for search data",
 *  name="q",
 *      @OA\Schema(
 *          type="string"
 *      )
 *  )
 *
 * @OA\Parameter(
 *  in="query",
 *  parameter="OA_method_put",
 *  name="_method",
 *  schema={"type": "string", "enum": {"PUT"}, "default": "PUT"},
 *  required=true
 *  )
 *
 * @OA\Parameter(
 *  in="query",
 *  parameter="OA_listType",
 *  name="type",
 *  description="Type of list",
 *  schema={"type": "string", "enum": {"collection", "pagination"}, "default": "pagination"}
 *  )
 *
 *  @OA\Parameter(
 *  in="query",
 *  parameter="OA_listPage",
 *  description="Number of page usefull if type is pagination",
 *  name="page",
 *      @OA\Schema(
 *          type="string"
 *      )
 *  )
 *
 * @OA\Parameter(
 *  in="query",
 *  parameter="OA_SortBy",
 *  name="sort_by",
 *  description="Sort by",
 *  schema={"type": "string", "enum": {"asc", "desc"}}
 * )
 *
 *
 * @OA\Parameter(
 *  in="query",
 *  parameter="OA_OrderBy",
 *  name="order_by",
 *  description="Order by",
 *      @OA\Schema(
 *          type="string"
 *      )
 *  )
 *
 *
 * @OA\Parameter(
 *  in="query",
 *  parameter="OA_limit",
 *  description="Limit data",
 *  name="limit",
 *      @OA\Schema(
 *          type="integer"
 *      )
 *  )
 *
 *  @OA\Parameter(
 *  in="query",
 *  parameter="OA_Relations",
 *  description="Get relations of the model",
 *  name="relations",
 *  schema={"type": "string"}
 * )
 *
 * @OA\Parameter(
 *  in="query",
 *  parameter="OA_is_active",
 *  name="is_active",
 *  schema={"type": "integer", "enum": {"1", "0"} },
 *  required=false
 * )
 *
 * @OA\Parameter(
 *  in="query",
 *  parameter="OA_is_enabled",
 *  name="is_enabled",
 *  schema={"type": "integer", "enum": {"1", "0"}},
 *  required=false
 * )
 *
 * @OA\Tag(
 *   name="Auth",
 * )
 *
 *
 *
 */





class Controller
{
}
