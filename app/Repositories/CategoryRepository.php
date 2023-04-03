<?php

namespace App\Repositories;

use App\Models\Category;

class CategoryRepository
{
    protected $category;

    public function __construct(Category $category)
    {
        $this->category = $category;
    }

    public function getCategoriesByUserId($user_id = null)
    {
        if ($user_id == null){
            return $this->category->all();
        }
        return $this->category
                ->where('user_id', null)
                ->orWhere('user_id', $user_id)
                ->get();
    }

    public function createCategory($request)
    {
        $category = $this->category;
        $category->category_name_en = $request->categoryNameEn;
        $category->user_id = $request->userId;
        $category->save();
        return $category;
    }

    public function getCategoryById($categoryId)
    {
        return $this->category->where('category_id', $categoryId)->first();
    }

    public function updateCategory($category, $request)
    {
        $category->category_name_en = $request->categoryNameEn;
        $category->save();
        return $category;
    }

    public function deleteCategory($category)
    {
        return $category->delete();
    }
}
