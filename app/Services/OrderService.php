<?php
namespace App\Services;

use App\Repositories\OrderRepository;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\File;


class OrderService
{
    public function __construct(OrderRepository $orderRepository)
    {
        $this->repository = $orderRepository;
    }

    public function listOrders()
    {
        $result = $this->repository->getOrdersGroupForClient();

        $groupForOrder = [];

        $grouped = $result->groupBy('client_id')->toArray();

        foreach ($grouped as $client_id => $orders) {
            foreach ($orders as $itemOrder) {
                $dateConverted = date("Y-m-d H:i:s", strtotime($itemOrder['created_at']));
                $groupForOrder[$client_id][$dateConverted][] = $itemOrder;
            }
        }

        return $groupForOrder;
    }

    public function OrderForId($clientId)
    {
        return $this->repository->getOrderTodayClient($clientId);
    }

    public function createOrder($request)
    {
        return $this->repository->saveOrder($request);
    }

    public function removeOrder($clientId) {
        
        if (!count($this->repository->getOrderTodayClient($clientId))) {
            return response()->json([
                'status' => true,
                'message' => 'There is no order from this client today to be canceled',
            ], 401);
        }
        
        $this->repository->deleteOrder($clientId);

        return response()->json([
            'status' => true,
            'message' => 'Order Deleted',
        ], 200);
    }

    public function updateOrderToday($request) {
        return $this->repository->updateOrder($request);
    }
}
