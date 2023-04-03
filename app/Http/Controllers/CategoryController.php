<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\CategoryRepository;

class CategoryController extends Controller
{
    public function __construct(private CategoryRepository $categoryRepository)
    {

    }

    public function index()
    {
        $userId = 1;
        $categoryList = $this->categoryRepository->getCategoriesByUserId($userId);
        return response()->json([
            'categories' => $categoryList
        ]);
    }

    public function store(Request $request)
    {
        try{
            $category = $this->categoryRepository->createCategory($request);
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

    public function update(Request $request, $categoryId)
    {
        $category = $this->show($categoryId);

        if(!$category) {
            return response()->json([
                'success' => false,
                'message' => 'Category not found'
            ], 404);
        }

        try{
            $category = $this->categoryRepository->updateCategory($category, $request);
        }
        catch(\Exception $e){
            return response()->json([
                'success' => false,
                'message' => 'Category update failed: ' . $e->getMessage()
            ], 400);
        }

        return response()->json([
            'success' => true,
            'message' => 'Category updated successfully',
            'category' => $category
        ], 200);
    }

    public function destroy($categoryId)
    {
        $category = $this->show($categoryId);

        if(!$category) {
            return response()->json([
                'success' => false,
                'message' => 'Category not found'
            ], 404);
        }

        try {
            $this->categoryRepository->deleteCategory($category);
        }
        catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Category deletion failed: ' . $e->getMessage()
            ], 400);
        }

        return response()->json([
            'success' => true,
            'message' => 'Category deleted successfully'
        ], 200);
    }

    public function show($categoryId)
    {
        return $this->categoryRepository->getCategoryById($categoryId);
    }
}
