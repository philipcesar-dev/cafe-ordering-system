<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class MenuController extends Controller
{
    // GET /api/menu
    public function index()
    {
        $menus = Menu::where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Menu retrieved successfully',
            'data'    => $menus
        ], 200);
    }

    // GET /api/menu/{id}
    public function show($id)
    {
        $menu = Menu::find($id);

        if (!$menu) {
            return response()->json([
                'success' => false,
                'message' => 'Menu item not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Menu item retrieved successfully',
            'data'    => $menu
        ], 200);
    }

    // POST /api/menu
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'product_id'  => 'nullable|exists:products,id',
                'name'        => 'required|string|max:255',
                'description' => 'nullable|string',
                'price'       => 'required|numeric|min:0',
                'category'    => 'nullable|string|max:255',
                'is_active'   => 'boolean',
                'sort_order'  => 'nullable|integer|min:0',
                'image'       => 'nullable|image|max:2048',
            ]);

            $validated['slug'] = Str::slug($validated['name']);
            $validated['is_active'] = $request->boolean('is_active', true);
            $validated['sort_order'] = $validated['sort_order'] ?? 0;

            if ($request->hasFile('image')) {
                $validated['image'] = $request->file('image')->store('menus', 'public');
            }

            $menu = Menu::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Menu item created successfully',
                'data'    => $menu
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
                'message' => 'Failed to create menu item',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    // PUT / PATCH /api/menu/{id}
    public function update(Request $request, $id)
    {
        $menu = Menu::find($id);

        if (!$menu) {
            return response()->json([
                'success' => false,
                'message' => 'Menu item not found'
            ], 404);
        }

        try {
            $validated = $request->validate([
                'product_id'  => 'nullable|exists:products,id',
                'name'        => 'sometimes|required|string|max:255',
                'description' => 'nullable|string',
                'price'       => 'sometimes|required|numeric|min:0',
                'category'    => 'nullable|string|max:255',
                'is_active'   => 'boolean',
                'sort_order'  => 'nullable|integer|min:0',
                'image'       => 'nullable|image|max:2048',
            ]);

            if (isset($validated['name'])) {
                $validated['slug'] = Str::slug($validated['name']);
            }

            if ($request->has('is_active')) {
                $validated['is_active'] = $request->boolean('is_active');
            }

            if ($request->hasFile('image')) {
                if ($menu->image) {
                    Storage::disk('public')->delete($menu->image);
                }
                $validated['image'] = $request->file('image')->store('menus', 'public');
            }

            $menu->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Menu item updated successfully',
                'data'    => $menu
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
                'message' => 'Failed to update menu item',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    // DELETE /api/menu/{id}
    public function destroy($id)
    {
        $menu = Menu::find($id);

        if (!$menu) {
            return response()->json([
                'success' => false,
                'message' => 'Menu item not found'
            ], 404);
        }

        try {
            if ($menu->image) {
                Storage::disk('public')->delete($menu->image);
            }

            $menu->delete();

            return response()->json([
                'success' => true,
                'message' => 'Menu item deleted successfully'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete menu item',
                'error'   => $e->getMessage()
            ], 500);
        }
    }
}