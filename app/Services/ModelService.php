<?php

namespace App\Services;

abstract class ModelService implements ApiService
{
    /**
     * @return mixed
     */
    public function all()
    {
        return $this->model->all();
    }

    /**
     * @param array $input
     * @return mixed
     */
    public function create(array $input)
    {
        return $this->model->create($input);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function find($id)
    {
        return $this->model->findOrFail($id);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function delete($id)
    {
        return $this->find($id)->delete();
    }

    /**
     * @param $id
     * @param array $input
     * @return mixed
     */
    public function update($id, array $input)
    {
        return $this->find($id)->update($input);
    }

    /**
     * @param string $column
     * @param string $value
     * @return mixed
     */
    public function firstWhere(string $column, string $value)
    {
        return $this->model->firstWhere($column, $value);
    }
}
