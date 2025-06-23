<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CategoryController extends Controller
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

        $categoriesQuery = Category::query();

        $categoriesQuery->when($search, function ($query, $search) {
            $query->where('name', 'like', '%' . $search . '%');
        });

        $categoriesQuery->orderBy($sortBy, $sortDesc ? 'desc' : 'asc');

        $categories = $categoriesQuery->paginate($perPage)->withQueryString();

        $can = [
            'createCategory' => $request->user()->can('create categories'),
            'editCategory' => $request->user()->can('edit categories'),
            'deleteCategory' => $request->user()->can('delete categories'),
        ];

        return Inertia::render('Categories/Index', [
            'categories' => $categories,
            'can' => $can,
            'initialSortBy' => [
                ['key' => $sortBy, 'order' => $sortDesc ? 'desc' : 'asc']
            ]
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|min:3|max:100|unique:categories,name',
        ]);

        Category::create([
            'name' => $request->name,
        ]);

        return back()->with('success', 'Categoría creada exitosamente');
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => [
                'required',
                'string',
                'min:3',
                'max:100',
                \Illuminate\Validation\Rule::unique('categories', 'name')->ignore($category->id),
            ],
        ]);

        $category->update([
            'name' => $request->name,
        ]);

        return back()->with('success', 'Categoría actualizada exitosamente');
    }

    public function destroy(Category $category)
    {
        $category->delete();

        return back()->with('success', 'Categoría eliminada exitosamente');
    }
}