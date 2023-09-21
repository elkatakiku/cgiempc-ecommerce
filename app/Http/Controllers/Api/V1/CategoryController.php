<?php

namespace App\Http\Controllers\Api\V1;

use App\Actions\Category\CreateCategory;
use App\Actions\Category\UpdateCategory;
use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryStoreRequest;
use App\Http\Requests\CategoryUpdateRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class CategoryController extends Controller
{

    public function __construct()
    {
        $this->middleware('role:admin')->except('show');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
//        TODO: Filter data
        return CategoryResource::collection(Category::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CategoryStoreRequest $request, CreateCategory $action)
    {
        try {
            $category = $action->handle($request->name);
        } catch (\Exception $e) {
            return abort(Response::HTTP_INTERNAL_SERVER_ERROR, 'Could not create a new category, please try again later.');
        }

        return response()->json([
            'message' => 'Category ' . $category->name . ' created successfully.',
        ], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        Gate::authorize('view', Category::class);

        return CategoryResource::make($category);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CategoryUpdateRequest $request, Category $category, UpdateCategory $action)
    {
        try {
            $action->handle($category, $request->name);
        } catch (\Exception $e) {
            return abort(Response::HTTP_INTERNAL_SERVER_ERROR, 'Could not update the category, please try again later.');
        }

        return response()->json([
            'message' => 'Category updated successfully.',
        ], Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        Gate::authorize('delete', $category);

        try {
            $category->delete();
        } catch (\Exception) {
            return abort(Response::HTTP_INTERNAL_SERVER_ERROR, 'Could not delete category ' . $category->name . ', please try again later.');
        }

        return \response()->json(
            ['message' => 'Category ' . $category->name . ' deleted successfully.'],
            Response::HTTP_OK,
        );
    }
}
