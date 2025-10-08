<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

abstract class BaseRepository implements BaseRepositoryInterface
{
    private $model;

    /**
     * @throws \Exception
     */
    public function __construct()
    {
        $this->makeModel();
    }

    abstract public function model(): string;

    protected function makeModel()
    {
        $model = resolve($this->model());

        if (! $model instanceof Model) {
            throw new \Exception("Class {$this->model()} must be an instance of Illuminate\\Database\\Eloquent\\Model");
        }
        $this->model = $model;
    }
    protected function query(): \Illuminate\Database\Eloquent\Builder
    {
        return $this->model->newQuery();
    }

    public function create($data)
    {
        return $this->query()->create($data);
    }

    public function update($id, $data): int
    {
        return $this->query()->where("id", $id)->first()?->update($data);
    }

    public function delete($id)
    {
        return $this->query()->where("id", $id)->first()?->delete();
    }

    public function findById($id, $with=[])
    {
        return $this->query()->with($with)->where("id", $id)->first();
    }

    public function firstOrFail($id, $with=[])
    {
        return $this->query()->with($with)->where("id", $id)->firstOrFail();
    }

    public function filter()
    {
        return $this->query()->filter();
    }
}
