<?php

namespace App\Http\Controllers;

use App\Http\Requests\Category\CategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (!auth()->user()->can('view_categories')) {
            return apiResponse(403 , 'You Can Not View Category');
        }
        $categories = Category::paginate();

        return apiResponse(200, 'Categories retrieved successfully', $categories);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CategoryRequest $request)
    {
        if (!auth()->user()->can('create_category')) {
            return apiResponse(403 , 'You Can Not Create Category');
        }

        $category = Category::create($request->validated());

        return apiResponse(201, 'Category created successfully', $category);
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        return apiResponse(200, 'Category retrieved successfully', $category);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CategoryRequest $request, Category $category)
    {
        if (!auth()->user()->can('edit_category')) {
            return apiResponse(403 , 'You Can Not Edit Category');
        }

        $category->update($request->validated());

        return apiResponse(200, 'Category updated successfully', $category);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        if (!auth()->user()->can('delete_category')) {
            return apiResponse(403 , 'You Can Not Delete Category');
        }

        $category->delete();

        return apiResponse(200, 'Category deleted successfully', null);
    }
}
