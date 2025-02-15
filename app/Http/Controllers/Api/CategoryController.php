<?php

namespace App\Http\Controllers\Api;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CategoryController extends Controller
{

    public function index()
    {
        return response()->json(Category::whereNull('deleted_at')->get(), 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:categories',
        ]);

        $category = Category::create($request->only('name'));

        return response()->json($category, 201);
    }

    public function show($id)
    {
        $category = Category::whereNull('deleted_at')->find($id);
        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }
        return response()->json($category, 200);
    }

    public function update(Request $request, $id)
    {
        $category = Category::whereNull('deleted_at')->find($id);
        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }

        $request->validate(['name' => 'required|string|unique:categories,name,' . $id]);
        $category->update($request->only('name'));

        return response()->json($category, 200);
    }

    public function destroy($id)
    {
        $category = Category::find($id);
        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }

        $category->delete();
        return response()->json(['message' => 'Category deleted'], 200);
    }
}
