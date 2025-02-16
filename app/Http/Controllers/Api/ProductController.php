<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
 * @OA\Get(
 *     path="/api/products",
 *     summary="Get all products",
 *     tags={"Products"},
 *     security={{"sanctum": {}}},
 *     @OA\Response(response=200, description="Success"),
 *     @OA\Response(response=401, description="Unauthorized")
 * )
 */
    public function index(Request $request)
    {
        $query = Product::whereNull('deleted_at')->with('category:id,name'); 

        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where('name', 'like', "%{$search}%");
        }

        $products = $query->get()->map(function ($product) {
            return [
                'id' => $product->id,
                'name' => $product->name,
                'description' => $product->description,
                'price' => $product->price,
                'stock' => $product->stock,
                'category' => $product->category ? $product->category->name : null, 
                'category_id' => $product->category ? $product->category->id : null, 
                'image_url' => $product->image_url,
                'created_at' => $product->created_at,
                'updated_at' => $product->updated_at,
            ];
        });

        return response()->json($products);
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'category_id' => 'required|exists:categories,id',
            'image_url' => 'nullable|url',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $product = Product::create($validator->validated());
        return response()->json($product, 201);
    }

    public function show($id)
    {
        $product = Product::whereNull('deleted_at')->find($id);
        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }
        return response()->json($product, 200);
    }

    public function update(Request $request, $id)
    {
        $product = Product::whereNull('deleted_at')->find($id);
        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'price' => 'sometimes|numeric',
            'stock' => 'sometimes|integer',
            'category_id' => 'sometimes|exists:categories,id',
            'image_url' => 'nullable|url',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $product->update($validator->validated());
        return response()->json($product, 200);
    }

    public function destroy($id)
    {
        $product = Product::whereNull('deleted_at')->find($id);
        
        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        $product->update(['deleted_at' => now()]);

        return response()->json(['message' => 'Product deleted successfully']);
    }

}
