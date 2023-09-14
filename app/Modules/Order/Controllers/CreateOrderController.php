<?php

namespace App\Modules\Order\Controllers;

use App\Modules\Order\Requests\CreateOrderRequest;
use App\Modules\Order\Resources\OrderResource;
use App\Modules\Order\Services\CreateOrderService;
use Illuminate\Http\Response;

class CreateOrderController
{
    public function __construct(
        protected CreateOrderService $service,
    )
    {
    }

    public function __invoke(CreateOrderRequest $request): \Illuminate\Http\JsonResponse
    {
        $order = $this->service->handle($request->get('products'));

        return response()->json(new OrderResource($order), Response::HTTP_CREATED);
    }
}