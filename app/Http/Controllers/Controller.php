<?php

namespace App\Http\Controllers;
use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *     title="API swagger",
 *     version="1.0",
 *     description="API Pezeshk hub",
 * ),
 * @OA\SecurityScheme(
 *          securityScheme="bearerAuth",
 *          type="http",
 *          scheme="bearer",
 *          bearerFormat="token"
 *      )
 * @OA\Server(url="http://localhost")
 */


abstract class Controller
{
    //
}
