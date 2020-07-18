<?php

namespace App\Services;

abstract class ModelService implements ApiService
{
    public function all()
    {
        return $this->model->all();
    }

    public function create(array $input)
    {
        return $this->model->create($input);
    }

    public function find($id)
    {
        return $this->model->findOrFail($id);
    }

    public function delete($id)
    {
        return $this->find($id)->delete();
    }

    public function update($id, array $input)
    {
        return $this->find($id)->update($input);
    }

    public function firstWhere(string $column, string $value)
    {
        return $this->model->firstWhere($column, $value);
    }
}
