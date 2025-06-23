<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException; // Import this class

class ProductApiController extends Controller
{
    /**
     * @OA\Get(
     * path="/products",
     * summary="Listar todos los productos",
     * tags={"Products"},
     * @OA\Response(
     * response=200,
     * description="Lista de productos",
     * @OA\JsonContent(
     * type="object",
     * @OA\Property(property="status", type="string", example="success"),
     * @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Product"))
     * )
     * )
     * )
     */
    public function index(Request $request)
    {
        $products = Product::with('categories')->get();

        return response()->json([
            'status' => 'success',
            'data' => $products
        ]);
    }

    /**
     * @OA\Get(
     * path="/products/{product}",
     * summary="Obtener un producto por ID",
     * tags={"Products"},
     * @OA\Parameter(
     * name="product",
     * in="path",
     * required=true,
     * description="ID del producto a obtener",
     * @OA\Schema(
     * type="integer",
     * format="int64",
     * example=1
     * )
     * ),
     * @OA\Response(
     * response=200,
     * description="Detalles del producto",
     * @OA\JsonContent(
     * type="object",
     * @OA\Property(property="status", type="string", example="success"),
     * @OA\Property(property="data", ref="#/components/schemas/Product")
     * )
     * ),
     * @OA\Response(
     * response=404,
     * description="Producto no encontrado",
     * @OA\JsonContent(
     * type="object",
     * @OA\Property(property="status", type="string", example="error"),
     * @OA\Property(property="message", type="string", example="Producto no encontrado")
     * )
     * )
     * )
     */
    public function show(Product $product)
    {
        try {
            $product->load('categories'); // Eager load categories for the product
            return response()->json([
                'status' => 'success',
                'data' => $product
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Producto no encontrado'
            ], 404);
        }
    }

    /**
     * @OA\Get(
     * path="/categories/{category}/products",
     * summary="Obtener productos por categoría",
     * tags={"Categories"},
     * @OA\Parameter(
     * name="category",
     * in="path",
     * required=true,
     * description="ID de la categoría para filtrar productos",
     * @OA\Schema(
     * type="integer",
     * format="int64",
     * example=1
     * )
     * ),
     * @OA\Response(
     * response=200,
     * description="Productos por categoría",
     * @OA\JsonContent(
     * type="object",
     * @OA\Property(property="status", type="string", example="success"),
     * @OA\Property(property="category", type="object",
     * @OA\Property(property="id", type="integer", example=1),
     * @OA\Property(property="name", type="string", example="Electrónica")
     * ),
     * @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Product"))
     * )
     * ),
     * @OA\Response(
     * response=404,
     * description="Categoría no existe",
     * @OA\JsonContent(
     * type="object",
     * @OA\Property(property="status", type="string", example="error"),
     * @OA\Property(property="message", type="string", example="Categoría no existe")
     * )
     * )
     * )
     */
    public function productsByCategory(Category $category)
    {
        try {
            // Route Model Binding handles the initial check for the category's existence.
            // If the category is not found, a ModelNotFoundException will be thrown
            // before this code even executes. The catch block handles that.
            $products = $category->products()->with('categories')->get();

            return response()->json([
                'status' => 'success',
                'category' => [
                    'id' => $category->id,
                    'name' => $category->name,
                ],
                'data' => $products
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Categoría no existe'
            ], 404);
        }
    }
}