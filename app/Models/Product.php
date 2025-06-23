<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany; 

/**
 * @OA\Schema(
 * schema="Product",
 * title="Product",
 * description="Modelo de Producto",
 * @OA\Property(property="id", type="integer", readOnly=true, example=1),
 * @OA\Property(property="name", type="string", example="Smartphone X"),
 * @OA\Property(property="description", type="string", nullable=true, example="Un teléfono inteligente de última generación."),
 * @OA\Property(property="price", type="number", format="float", example=599.99),
 * @OA\Property(property="expiration_date", type="string", format="date", nullable=true, example="2025-12-31"),
 * @OA\Property(property="created_at", type="string", format="date-time", readOnly=true, example="2024-01-01T10:00:00Z"),
 * @OA\Property(property="updated_at", type="string", format="date-time", readOnly=true, example="2024-01-01T10:00:00Z"),
 * @OA\Property(property="categories", type="array", @OA\Items(ref="#/components/schemas/Category"), description="Categorías a las que pertenece el producto")
 * )
 */

class Product extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'price',            // Correct: This matches your DB column
        'expiration_date',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'expiration_date' => 'date',   
        'price' => 'decimal:2',         
    ];

    /**
     * The categories that belong to the product.
     */
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }
}