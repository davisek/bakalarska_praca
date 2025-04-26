<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * @OA\Info(
 *     title="Sensor Management API Documentation",
 *     version="1.0.0",
 *     description="API Documentation for Sensor Management System",
 *     @OA\Contact(
 *         email="",
 *         name=""
 *     )
 * )
 *
 * @OA\Server(
 *     url="/api",
 *     description="API Server"
 * )
 *
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT"
 * )
 *
 * @OA\SecurityScheme(
 *     securityScheme="sensorAdminKey",
 *     type="apiKey",
 *     in="header",
 *     name="X-Admin-Token"
 * )
 */
class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
}
