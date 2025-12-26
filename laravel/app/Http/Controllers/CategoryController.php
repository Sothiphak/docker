<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category; // Important: Import the Model

class CategoryController extends Controller
{
    // Retrieve all categories
    public function getCategories(){
        return Category::all();
    }

    // Create a new category
    public function createCategory(Request $request){
        $category = Category::create([
            'name' => $request->name
        ]);
        return response()->json($category, 201);
    }

    // Retrieve one category by ID
    public function getCategory($categoryId){
        return Category::findOrFail($categoryId);
    }

    // Update a category
    public function updateCategory(Request $request, $categoryId){
        $category = Category::findOrFail($categoryId);
        $category->update($request->all());
        return $category;
    }

    // Delete a category
    public function deleteCategory($categoryId){
        $category = Category::findOrFail($categoryId);
        $category->delete();
        return response()->json(["message" => "Category deleted successfully"]);
    }
}
