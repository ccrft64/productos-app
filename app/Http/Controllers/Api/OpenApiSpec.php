<?php

namespace App\Http\Controllers\Api;

/**
 * @OA\Info(
 * version="1.0.0",
 * title="API de Productos",
 * description="Documentación de la API para la gestión de productos y categorías.",
 * @OA\Contact(
 * email="alvarocerdamarin@gmail.com"
 * ),
 * @OA\License(
 * name="Apache 2.0",
 * url="http://www.apache.org/licenses/LICENSE-2.0.html"
 * )
 * )
 *
 * @OA\Server(
 * url="http://localhost:8000/api",
 * description="Servidor de desarrollo"
 * )
 *
 * @OA\Tag(
 * name="Productos",
 * description="Operaciones relacionadas con los productos"
 * )
 * @OA\Tag(
 * name="Categorías",
 * description="Operaciones relacionadas con las categorías y sus productos"
 * )
 */
class OpenApiSpec
{
    // Este archivo es solo para anotaciones
}