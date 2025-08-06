<?php

namespace App\Http\Controllers;

/**
 * @OA\Info(
 *     title="API de Parkings",
 *     version="1.0",
 *     description="API REST desarrollada en Laravel para la gestión de parkings.",
 *     @OA\Contact(
 *         email="tu-email@ejemplo.com"
 *     )
 * )
 */
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

use OpenApi\Annotations as OA;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}
