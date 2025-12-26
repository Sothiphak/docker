<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product; // Important: Import the Model

class ProductController extends Controller
{
    public function getProducts() {
        return Product::all();
    }

    public function createProduct(Request $request) {
        $product = Product::create($request->all());
        return response()->json($product, 201);
    }

    public function getProduct($productId) {
        return Product::findOrFail($productId);
    }

    public function updateProduct(Request $request, $productId) {
        $product = Product::findOrFail($productId);
        $product->update($request->all());
        return $product;
    }

    public function deleteProduct($productId) {
        $product = Product::findOrFail($productId);
        $product->delete();
        return response()->json(["message" => "Product deleted"]);
    }

    // Get products belonging to a specific category
    public function getProductsByCategory($categoryId) {
        return Product::where('category_id', $categoryId)->get();
    }
}
