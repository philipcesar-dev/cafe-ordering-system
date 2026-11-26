<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit Product</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <x-input-label for="category_id" value="Category" />
                        <select id="category_id" name="category_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <x-input-label for="name" value="Name" />
                        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $product->name)" required />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <div class="mb-4">
                        <x-input-label for="description" value="Description" />
                        <textarea id="description" name="description" rows="3"
                                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">{{ old('description', $product->description) }}</textarea>
                    </div>

                    <div class="mb-4">
                        <x-input-label for="price" value="Price (Rp)" />
                        <x-text-input id="price" name="price" type="number" step="100" min="0" class="mt-1 block w-full" :value="old('price', $product->price)" required />
                    </div>

                    <div class="mb-4">
                        <x-input-label for="image" value="Image" />
                        @if($product->image)
                            <div class="mb-2">
                                <img src="{{ asset('storage/' . $product->image) }}" class="w-24 h-24 object-cover rounded">
                            </div>
                        @endif
                        <input type="file" id="image" name="image" accept="image/*" class="mt-1 block w-full text-sm">
                        <p class="text-xs text-gray-500 mt-1">Leave empty to keep current image</p>
                    </div>

                    <div class="mb-4">
                        <x-input-label for="stock" value="Stock" />
                        <x-text-input id="stock" name="stock" type="number" min="0" class="mt-1 block w-full" :value="old('stock', $product->stock)" />
                    </div>

                    <div class="mb-6">
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="is_available" value="1" class="rounded border-gray-300 text-indigo-600"
                                   {{ old('is_available', $product->is_available) ? 'checked' : '' }}>
                            <span class="ml-2 text-sm text-gray-600">Available</span>
                        </label>
                    </div>

                    <div class="flex justify-end gap-3">
                        <a href="{{ route('admin.products.index') }}" class="px-4 py-2 bg-gray-200 rounded-md">Cancel</a>
                        <button type="submit" class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-md">Update Product</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>