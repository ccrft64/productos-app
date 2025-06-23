<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany; 


/**
 * @OA\Schema(
 * schema="Category",
 * title="Category",
 * description="Modelo de Categoría",
 * @OA\Property(property="id", type="integer", readOnly=true, example=1),
 * @OA\Property(property="name", type="string", example="Electrónica"),
 * @OA\Property(property="created_at", type="string", format="date-time", readOnly=true, example="2024-01-01T10:00:00Z"),
 * @OA\Property(property="updated_at", type="string", format="date-time", readOnly=true, example="2024-01-01T10:00:00Z")
 * )
 */

class Category extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
    ];

    /**
     * The products that belong to the category.
     */
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class);
    }
}