<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class MainController extends Controller
{
    public function index()
    {
        try {
            $categories = Category::all();
            return response()->json($categories, 200);
        } catch (\Exception $e) {
            Log::error('Error fetching categories: ' . $e->getMessage());
            return response()->json(['error' => 'Error occurred while fetching categories', 'details' => $e->getMessage()], 500);
        }
    }

    public function getAllCategory()
    {
        try {
            $categories = Category::all();
            return response()->json($categories, 200);
        } catch (\Exception $e) {
            Log::error('Error fetching categories: ' . $e->getMessage());
            return response()->json(['error' => 'Error occurred while fetching categories', 'details' => $e->getMessage()], 500);
        }
    }

    public function create()
    {
        try {
            $categoryData = [
                'name' => ''
            ];
            return response()->json($categoryData, 200);
        } catch (\Exception $e) {
            Log::error('Error preparing category creation: ' . $e->getMessage());
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
            Log::error('Error creating category: ' . $e->getMessage());
            return response()->json(['error' => 'Error occurred while creating category', 'details' => $e->getMessage()], 500);
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
            Log::error('Error updating category: ' . $e->getMessage());
            return response()->json(['error' => 'Error occurred while updating category', 'details' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $category = Category::findOrFail($id);
            $category->delete();
            return response()->json(['success' => 'Category deleted successfully'], 204);
        } catch (\Exception $e) {
            Log::error('Error deleting category: ' . $e->getMessage());
            return response()->json(['error' => 'Error occurred while deleting category', 'details' => $e->getMessage()], 500);
        }
    }
}
