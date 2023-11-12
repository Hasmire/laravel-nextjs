<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * @OA\Info(
 *      version="1.0.0",
 *      x={
 *          "logo": {
 *              "url": "https://via.placeholder.com/190x90.png?text=L5-Swagger"
 *          }
 *      },
 *      title="Marita Salon Client Record Management System",
 *      description="API for managing client records in Marita Salon. This API provides endpoints for various features including authentication, analytics/dashboard, transactions, clients, employees, services/items, manager operations, and user profiles.",
 * )
 *
 * @OA\Tag(
 *     name="Authentication",
 *     description="Endpoints for user authentication"
 * )

 * @OA\Tag(
 *     name="User Profile",
 *     description="Endpoints for managing user profiles",
 * )
 *
 * @OA\ExternalDocumentation(
 *     description="Find out more about Swagger and OpenApi",
 *     url="https://swagger.io"
 * )
 *
 * @OA\Schema(
 *     schema="ErrorResponse",
 *     title="Error Response",
 *
 *     @OA\Property(property="message", type="string", description="Error message"),
 *     @OA\Property(property="code", type="integer", description="HTTP status code"),
 * )
 */
class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
}
