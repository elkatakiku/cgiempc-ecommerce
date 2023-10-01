<?php

namespace App\Http\Controllers\Api\V1;

use App\Actions\Order\CreateOrder;
use App\Actions\Order\UpdateOrder;
use App\Http\Controllers\Controller;
use App\Http\Requests\Order\StoreOrderRequest;
use App\Http\Requests\Order\UpdateOrderRequest;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class OrderController extends Controller
{
//    TODO: Edit order #.

    /**
     * Display a listing of orders.
     */
    public function index(Request $request)
    {
        Gate::authorize('view-any', Order::class);

        $orders = Order::query()
            ->with('user', 'product')
            ->when($request->product, function ($query) use ($request) {
                if (Product::query()->where('slug', $request->product)->exists()) {
                    $query->where('product_id', $request->product);
                }
            })
            ->when($request->status, function ($query) use ($request) {
                $query->where('status', $request->status);
            })
            ->when($request->payment_type, function ($query) use ($request) {
                $query->where('payment_type', $request->payment_type);
            });

        if ($request->user()->role->isAdmin()) {
//            Get all orders. If user is specified, get all orders of that user.
            $orders->when($request->user, function ($query) use ($request) {
                $query->where('user_id', $request->user);
            });
        } else {
//            Get all orders of user
            $orders->where('user_id', $request->user()->id);
        }

        return OrderResource::collection($orders->get());
    }

    /**
     * Store a newly created order in storage.
     */
    public function store(StoreOrderRequest $request, CreateOrder $action)
    {
        try {
            $action->handle($request->validated(), $request->user()->id);
        } catch (\Exception $exception) {
            Log::info(__METHOD__);
            Log::info($exception->getMessage());
            return abort(
                Response::HTTP_INTERNAL_SERVER_ERROR,
                'Could not create order, please try again later. If error persists, contact the admin.'
            );
        }

        return response()->json([
            'message' => 'Order created successfully.',
        ], Response::HTTP_CREATED);
    }

    /**
     * Display the specified order.
     */
    public function show(Order $order)
    {
        Gate::authorize('view', $order);

        return OrderResource::make($order);
    }

    /**
     * Update the specified order in storage.
     */
    public function update(UpdateOrderRequest $request, Order $order, UpdateOrder $action)
    {
        try {
            $action->handle($order, $request->validated());
        } catch (\Exception $e) {
            return abort(
                Response::HTTP_INTERNAL_SERVER_ERROR,
                'Could not update order, please try again later. If error persists, contact the admin.'
            );
        }

        return response()->json([
            'message' => 'Order updated successfully.',
        ], Response::HTTP_OK);
    }

    /**
     * Remove the specified order from storage.
     */
    public function destroy(Order $order)
    {
        Gate::authorize('delete', $order);

        try {
            $order->delete();
        } catch (\Exception) {
            return abort(
                Response::HTTP_INTERNAL_SERVER_ERROR,
                'Could not delete order, please try again later. If error persists, contact the admin.'
            );
        }

        return \response()->json(
            ['message' => 'Order deleted successfully.'],
            Response::HTTP_OK,
        );
    }
}
