<?php
namespace App\Repositories;

use App\Models\Order;
use App\Models\Client;
use Illuminate\Support\Facades\Notification;
use App\Notifications\NewOrder;
use PostScripton\Money\Casts\MoneyCast;


class OrderRepository extends BaseEloquentRepository
{
    protected $model = Order::class;

    public function sendNotification($clientId, $result) {

        $data = $this->getDataNotification($result, $clientId);

        Notification::route('mail',$data["email"])
            ->notify(new NewOrder($data));
    }

    public function getDataNotification($result, $clientId){

        $client = Client::find($clientId);

        $valueitems = $result->pluck('product.price');
        $sumTotal = $valueitems->sum();

        $data = [
            "name" => $client->name,
            "email" => $client->email,
            "order" => $result->toArray(),
            "total" => number_format($sumTotal, 2)
        ];

        return $data;
    }

    public function saveOrder($request) {
        $client = Client::find($request->clientId);
        $client->products()->attach($request->products);

        $result = $this->getOrderTodayClient($request->clientId);

        $this->sendNotification($request->clientId, $result);

        return $result;
    }

    public function deleteOrder($clientId) {

        $productsOrder = $this->getOrderTodayClient($clientId);

        foreach($productsOrder as $product) {
            $product->delete();
        }
    }

    public function getOrdersGroupForClient() {
        return Order::with(['client','product'])
        ->whereRaw('Date(created_at) = CURDATE()')
        ->orderBy('id', 'asc', 'created_at', 'asc')
        ->get();
    }

    public function getOrderTodayClient($clienteId) {
        return Order::with(['client','product'])
        ->where("client_id",$clienteId)
        ->whereRaw('Date(created_at) = CURDATE()')
        ->get();
    }

    public function updateOrder($request) {
        $client = Client::find($request["clientId"]);
        $client->products()->wherePivot('created_at',  'like', date("Y-m-d")."%")->sync($request['products']);
        $result = $this->getOrderTodayClient($request["clientId"]);
        $this->sendNotification($request["clientId"], $result);
        return $result;
    }
}
