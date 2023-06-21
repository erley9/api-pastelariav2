<?php

namespace App\Repositories;

use App\Models\Client;

class ClientRepository extends BaseEloquentRepository
{
    protected $model = Client::class;

    public function getClient($clientId) {
        return $this->getById($clientId);
    }

    public function getAllClients() {
        return $this->getAll(
            [
                "id",
                "name",
                "email",
                "phonenumber",
                "dateofbirth",
                "address",
                "complement",
                "neighborhood",
                "zipcode"
            ],
            "clients.name",
            "asc"
        );
    }

    public function saveClient($request) {
        return $this->create($request);
    }

    public function changeClient($id, $request) {
        return $this->update($id, $request);
    }

    public function deleteClient($clientId) {
        return $this->delete($clientId);
    }
}
