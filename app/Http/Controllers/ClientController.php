<?php

namespace App\Http\Controllers;
use App\Models\Client;
use App\Services\ClientService;
use App\Http\Requests\StoreCreateClient;
use App\Http\Requests\StoreUpdateClient;
use DB;

class ClientController extends Controller
{
    public function __construct(ClientService $service)
    {
        $this->service = $service;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $clients = $this->service->listClients();
        } catch(Exception $e) {
            return response()->json($e->getMessage(),500);
        }

        return response()->json([
            'status' => true,
            'message' => 'Successfully',
            'clients' => $clients->toArray()
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCreateClient $request)
    {
        DB::beginTransaction();

        try {
            $client = $this->service->createClient($request->all());
        } catch(Exception $e) {
            DB::rollback();
            return response()->json($e->getMessage(),500);
        }

        DB::commit();

        return response()->json([
            'status' => true,
            'message' => 'Client Created Successfully',
            'client' => $client
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(Client $client)
    {
        try {
            $client = $this->service->clientForId($client->id);
        } catch(Exception $e) {
            return response()->json($e->getMessage(),500);
        }

        return response()->json([
            'status' => true,
            'message' => 'Client Found Successfully',
            'client' => $client->toArray()
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreUpdateClient $request, client $client)
    {
        DB::beginTransaction();

        try {
            $client = $this->service->updateClient($client->id, $request->all());
        } catch(Exception $e) {
            DB::rollback();
            return response()->json($e->getMessage(),500);
        }

        DB::commit();

        return response()->json([
            'status' => true,
            'message' => 'Client Updated Successfully',
            'client' => $client
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(client $client)
    {
        DB::beginTransaction();

        try {
            $client = $this->service->removeClient($client->id);
        } catch(Exception $e) {
            DB::rollback();
            return response()->json($e->getMessage()->message(),500);
        }

        DB::commit();

        return response()->json([
            'status' => true,
            'message' => 'Client Deleted'
        ], 200);
    }
}
