<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    public function getAllCategory()
    {
        try {
            $categories = Category::all();
            return response()->json($categories, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error occurred while fetching categories', 'details' => $e->getMessage()], 500);
        }
    }

    public function create()
    {
        try {
            // Return necessary data for creating a category, such as empty fields or default values
            $categoryData = [
                'name' => ''
            ];
            return response()->json($categoryData, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error preparing category creation', 'details' => $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 400);
            }

            $category = Category::create($request->all());
            return response()->json(['success' => 'Category added successfully', 'category' => $category], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error occurred while creating Category', 'details' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $category = Category::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 400);
            }

            $category->update($request->all());
            return response()->json(['success' => 'Category updated successfully', 'category' => $category], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error occurred while updating Category', 'details' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $category = Category::findOrFail($id);
            $category->delete();
            return response()->json(['success' => 'Category deleted successfully'], 204);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error occurred while deleting Category', 'details' => $e->getMessage()], 500);
        }
    }
}
