<?php

namespace App\Services;

use App\Repositories\Eloquent\BentoLayoutRepository;

readonly class BentoLayoutService
{
    public function __construct(private BentoLayoutRepository $bentoLayoutRepository)
    {
    }

    public function index()
    {
        return $this->bentoLayoutRepository->index();
    }

    public function create($data)
    {
        return $this->bentoLayoutRepository->create($data);
    }

    public function update($id, $data)
    {
        return $this->bentoLayoutRepository->update($id, $data);
    }

    public function findById($id)
    {
        return $this->bentoLayoutRepository->findById($id);
    }

    public function destroy($id)
    {
        return $this->bentoLayoutRepository->delete($id);
    }
}
