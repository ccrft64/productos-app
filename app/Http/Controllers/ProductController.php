<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        $search = $request->input('search');

        $sortBy = $request->input('sort_by');
        if (empty($sortBy)) {
            $sortBy = 'name';
        }

        $sortDesc = filter_var($request->input('sort_desc', false), FILTER_VALIDATE_BOOLEAN);

        $productsQuery = Product::query();

        $productsQuery->when($search, function ($query, $search) {
            $query->where('name', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%');
        });

        $productsQuery->orderBy($sortBy, $sortDesc ? 'desc' : 'asc');

        $products = $productsQuery->with('categories')->paginate($perPage)->withQueryString();

        $categories = Category::all(['id', 'name']);

        $foodCategory = Category::where('name', 'Alimentos')->first();

        $can = [
            'createProduct' => auth()->user() && auth()->user()->can('create products'),
            'editProduct' => auth()->user() && auth()->user()->can('edit products'),
            'deleteProduct' => auth()->user() && auth()->user()->can('delete products'),
        ];

        return Inertia::render('Products/Index', [
            'products' => $products,
            'categories' => $categories,
            'foodCategoryId' => $foodCategory ? $foodCategory->id : null,
            'can' => $can,
            'initialSortBy' => [
                ['key' => $sortBy, 'order' => $sortDesc ? 'desc' : 'asc']
            ]
        ]);
    }

    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|string|min:3|max:100',
            'price' => 'required|numeric|gt:0',
            'description' => 'nullable|string',
            'category_ids' => 'required|array',
            'category_ids.*' => 'exists:categories,id',
        ];

        $foodCategory = Category::where('name', 'Alimentos')->first();
        if ($foodCategory && in_array($foodCategory->id, $request->input('category_ids', []))) {
            $rules['expiration_date'] = 'required|date|after_or_equal:today';
        } else {
            $rules['expiration_date'] = 'nullable|date|after_or_equal:today';
        }

        $request->validate($rules);

        $product = Product::create($request->only('name', 'description', 'price', 'expiration_date'));
        $product->categories()->attach($request->input('category_ids'));

        return redirect()->route('products.index')->with('success', 'Producto creado exitosamente.');
    }

    public function update(Request $request, Product $product)
    {
        $rules = [
            'name' => 'required|string|min:3|max:100',
            'price' => 'required|numeric|gt:0',
            'description' => 'nullable|string',
            'category_ids' => 'required|array',
            'category_ids.*' => 'exists:categories,id',
        ];

        $foodCategory = Category::where('name', 'Alimentos')->first();
        if ($foodCategory && in_array($foodCategory->id, $request->input('category_ids', []))) {
            $rules['expiration_date'] = 'required|date|after_or_equal:today';
        } else {
            $rules['expiration_date'] = 'nullable|date|after_or_equal:today';
        }

        $request->validate($rules);

        $product->update($request->only('name', 'description', 'price', 'expiration_date'));
        $product->categories()->sync($request->input('category_ids'));

        return redirect()->route('products.index')->with('success', 'Producto actualizado exitosamente.');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Producto eliminado exitosamente.');
    }
}