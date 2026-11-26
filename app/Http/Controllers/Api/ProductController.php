<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class ProductController extends Controller
{
    /**
     * GET /api/products
     * Retrieve all products
     */
    public function index()
    {
        $products = Product::with('category')->latest()->get();

        return response()->json([
            'success' => true,
            'message' => 'Products retrieved successfully',
            'data'    => $products
        ], 200);
    }

    /**
     * GET /api/products/{id}
     * Retrieve a single product
     */
    public function show($id)
    {
        $product = Product::with('category')->find($id);

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Product retrieved successfully',
            'data'    => $product
        ], 200);
    }

    /**
     * POST /api/products
     * Create a new product
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'category_id'  => 'required|exists:categories,id',
                'name'         => 'required|string|max:255',
                'description'  => 'nullable|string',
                'price'        => 'required|numeric|min:0',
                'is_available' => 'boolean',
                'stock'        => 'nullable|integer|min:0',
                'image'        => 'nullable|image|max:2048',
            ]);

            $validated['slug'] = Str::slug($validated['name']);
            $validated['is_available'] = $request->boolean('is_available', true);

            if ($request->hasFile('image')) {
                $validated['image'] = $request->file('image')->store('products', 'public');
            }

            $product = Product::create($validated);
            $product->load('category');

            return response()->json([
                'success' => true,
                'message' => 'Product created successfully',
                'data'    => $product
            ], 201);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors'  => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create product',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    /**
     * PUT/PATCH /api/products/{id}
     * Update an existing product
     */
    public function update(Request $request, $id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found'
            ], 404);
        }

        try {
            $validated = $request->validate([
                'category_id'  => 'sometimes|required|exists:categories,id',
                'name'         => 'sometimes|required|string|max:255',
                'description'  => 'nullable|string',
                'price'        => 'sometimes|required|numeric|min:0',
                'is_available' => 'boolean',
                'stock'        => 'nullable|integer|min:0',
                'image'        => 'nullable|image|max:2048',
            ]);

            if (isset($validated['name'])) {
                $validated['slug'] = Str::slug($validated['name']);
            }

            if ($request->has('is_available')) {
                $validated['is_available'] = $request->boolean('is_available');
            }

            if ($request->hasFile('image')) {
                // Delete old image
                if ($product->image) {
                    Storage::disk('public')->delete($product->image);
                }
                $validated['image'] = $request->file('image')->store('products', 'public');
            }

            $product->update($validated);
            $product->load('category');

            return response()->json([
                'success' => true,
                'message' => 'Product updated successfully',
                'data'    => $product
            ], 200);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors'  => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update product',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    /**
     * DELETE /api/products/{id}
     * Delete a product
     */
    public function destroy($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found'
            ], 404);
        }

        try {
            // Delete image if exists
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }

            $product->delete();

            return response()->json([
                'success' => true,
                'message' => 'Product deleted successfully'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete product',
                'error'   => $e->getMessage()
            ], 500);
        }
    }
}