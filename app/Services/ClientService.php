<?php

namespace App\Services;

use App\Repositories\ClientRepository;

class ClientService
{
    public function __construct(ClientRepository $clientRepository)
    {
        $this->repository = $clientRepository;
    }

    public function listClients(){
        return $this->repository->getAllClients();
    }

    public function clientForId($clientId) {
        return $this->repository->getClient($clientId);
    }

    public function createClient($request)
    {
        $request["phonenumber"] = clearString($request["phonenumber"]);
        $request["zipcode"] = clearString($request["zipcode"]);
        return $this->repository->saveClient($request);
    }

    public function updateClient($clientId, $request)
    {
        $request["phonenumber"] = clearString($request["phonenumber"]);
        $request["zipcode"] = clearString($request["zipcode"]);
        unset($request["_method"]);
        $this->repository->changeClient($clientId, $request);
        return $this->repository->getById($clientId);
    }

    public function removeClient($clientId) {
        $this->repository->deleteClient($clientId);
    }
}
