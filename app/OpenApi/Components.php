<?php

namespace App\OpenApi;

/**
 * @OA\Schema(
 *     schema="LogResourceCollection",
 *     @OA\Property(
 *         property="data",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/LogResource")
 *     ),
 *     @OA\Property(
 *         property="links",
 *         type="object",
 *         @OA\Property(property="first", type="string"),
 *         @OA\Property(property="last", type="string"),
 *         @OA\Property(property="prev", type="string", nullable=true),
 *         @OA\Property(property="next", type="string", nullable=true)
 *     ),
 *     @OA\Property(
 *         property="meta",
 *         type="object",
 *         @OA\Property(property="current_page", type="integer"),
 *         @OA\Property(property="from", type="integer"),
 *         @OA\Property(property="last_page", type="integer"),
 *         @OA\Property(property="path", type="string"),
 *         @OA\Property(property="per_page", type="integer"),
 *         @OA\Property(property="to", type="integer"),
 *         @OA\Property(property="total", type="integer")
 *     )
 * )
 *
 * @OA\Schema(
 *     schema="SuccessResponse",
 *     @OA\Property(property="type", type="string", example="success"),
 *     @OA\Property(property="message", type="string", example="Created successfully")
 * )
 *
 * @OA\Schema(
 *     schema="ValidationErrorResponse",
 *     @OA\Property(property="type", type="string", example="error"),
 *     @OA\Property(property="message", type="string", example="The given data was invalid."),
 *     @OA\Property(
 *         property="errors",
 *         type="object",
 *         @OA\AdditionalProperties(
 *             type="array",
 *             @OA\Items(type="string")
 *         )
 *     )
 * )
 *
 * @OA\Parameter(
 *     parameter="PageParameter",
 *     name="page",
 *     in="query",
 *     description="Page number",
 *     required=false,
 *     @OA\Schema(type="integer", minimum=1)
 * )
 *
 * @OA\Parameter(
 *     parameter="PerPageParameter",
 *     name="per_page",
 *     in="query",
 *     description="Number of items per page",
 *     required=false,
 *     @OA\Schema(type="integer", minimum=1, maximum=100)
 * )
 *
 * @OA\Parameter(
 *     parameter="SearchParameter",
 *     name="search",
 *     in="query",
 *     description="Search term",
 *     required=false,
 *     @OA\Schema(type="string")
 * )
 *
 * @OA\Parameter(
 *     parameter="SortByParameter",
 *     name="sort_by",
 *     in="query",
 *     description="Field to sort by",
 *     required=false,
 *     @OA\Schema(type="string")
 * )
 *
 * @OA\Parameter(
 *     parameter="SortDirParameter",
 *     name="sort_dir",
 *     in="query",
 *     description="Sort direction",
 *     required=false,
 *     @OA\Schema(type="string", enum={"asc", "desc"})
 * )
 *
 * @OA\RequestBody(
 *     request="LogCreateRequest",
 *     required=true,
 *     @OA\JsonContent(ref="#/components/schemas/LogRequest")
 * )
 */
class Components
{

}