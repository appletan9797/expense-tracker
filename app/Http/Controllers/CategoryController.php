<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    public function getAllCategories(){
        $categoryList = Category::all();
        return response()->json([
            'categories' => $categoryList
        ]);
    }

    public function addCategory(Request $request){
        try{
            $category = new Category();
            $category->category_name_en = $request->categoryNameEn;
            $category->save();
        }
        catch(\Exception $e){
            return response()->json([
                'success' => false,
                'message' => 'Category creation failed: ' . $e->getMessage()
            ], 400);
        }

        return response()->json([
            'success' => true,
            'message' => 'Category created successfully',
            'Category' => $category
        ], 201);
    }
}
