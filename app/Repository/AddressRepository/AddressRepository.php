<?php

namespace App\Repository\AddressRepository;

use App\Interfaces\AddressInterface\AddressRepositoryInterface;
use App\Models\Address;

class AddressRepository implements AddressRepositoryInterface {

    protected Address $model;

    public function __construct(Address $model)
    {
        $this->model = $model;
    }

    public function getAll()
    {
        return $this->model->all();
    }

    public function findById(int $id)
    {
        return $this->model->find($id);
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update(Address $address, array $data)
    {
        return $address->update($data);
    }

    public function destroy(int $id)
    {
        return $this->model->destroy($id);
    }
}
