<?php

namespace App\Http\Controllers\Api\V1;

use App\Actions\Product\CreateProduct;
use App\Actions\Product\UpdateProduct;
use App\Http\Controllers\Controller;
use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        Gate::authorize('view-any', Product::class);

        //  TODO: Add search

        $products = Product::query()
            ->with('category')
            ->when($request->category, function ($query) use ($request) {
                $category = Category::query()->where('slug', $request->category)->first();

                if ($category) {
                    $query->where('category_id', $category->id);
                }
            })
            ->when($request->min, function ($query) use ($request) {
                $query->where('price', '>', $request->min);
            })
            ->when($request->max, function ($query) use ($request) {
                $query->where('price', '<', $request->max);
            })
            ->get();

        return ProductResource::collection($products);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request, CreateProduct $action)
    {
        try {
            $product = $action->handle($request->validated());
            Log::info(__METHOD__);
            Log::info($product->price);
        } catch (\Exception $exception) {
            Log::info(__METHOD__);
            Log::info($exception->getMessage());
            return abort(
                Response::HTTP_INTERNAL_SERVER_ERROR,
                'Could not create product, please try again later. If error persists, contact the admin.'
            );
        }

        return response()->json([
            'message' => 'Product created successfully.',
        ], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        Gate::authorize('view', $product);

        return ProductResource::make($product);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, Product $product, UpdateProduct $action)
    {
        try {
            $action->handle($product, $request->validated());
        } catch (\Exception $e) {
            return abort(
                Response::HTTP_INTERNAL_SERVER_ERROR,
                'Could not update product, please try again later. If error persists, contact the admin.'
            );
        }

        return response()->json([
            'message' => 'Product updated successfully.',
        ], Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        Gate::authorize('delete', $product);

        try {
            $product->delete();
        } catch (\Exception) {
            return abort(
                Response::HTTP_INTERNAL_SERVER_ERROR,
                'Could not delete product, please try again later. If error persists, contact the admin.'
            );
        }

        return \response()->json(
            ['message' => 'Product deleted successfully.'],
            Response::HTTP_OK,
        );
    }
}
