<?php

namespace App\Http\Controllers;

use App\Mail\NewOrderMember;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Services\OrderService;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\OrderRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class OrderController extends Controller
{
    /**
     * @var OrderService
     */
    private $orderService;

    /**
     * OrderController constructor.
     * @param OrderService $orderService
     */
    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    /**
     * @return JsonResponse
     */
    public function getOrders(): JsonResponse
    {
        return response()->json($this->orderService->getOrders());
    }

    /**
     * @return JsonResponse
     */
    public function userOrders(): JsonResponse
    {
        return response()->json($this->orderService->userOrders());
    }

    /**
     * @param OrderRequest $request
     * @return JsonResponse
     */
    public function storeOrder(OrderRequest $request): JsonResponse
    {
        DB::beginTransaction();

        try {
            $order = $this->orderService->storeOrder($request->all());

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            return response()->json([
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        Mail::to(auth()->user())->send(new NewOrderMember($order));

        return response()->json($order);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function deleteOrder(Request $request): JsonResponse
    {
        return response()->json($this->orderService->deleteOrder($request->id));
    }
}
