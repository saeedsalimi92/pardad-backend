<?php

namespace App\Services;



use App\Http\Requests\Admin\V1\Redirect\RedirectUpdateRequest;
use App\Http\Requests\Admin\V1\Redirect\RedirectStoreRequest;
use App\Models\Redirect;
use App\Repositories\RedirectRepositoryInterface;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

readonly class RedirectService
{
    public function __construct(private readonly RedirectRepositoryInterface $redirectRepository)
    {
    }
    public function index($data, $perPage = 15): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        return $this->redirectRepository->index($data, $perPage);
    }

    public function findById($id, $with=[])
    {
        return $this->redirectRepository->findById($id, $with);
    }

    public function create(RedirectStoreRequest $request)
    {
        $data = $request->validated();
        DB::beginTransaction();
        try {
            $redirect = $this->redirectRepository->create($data);
            DB::commit();
            return $redirect;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);
            return null;
        }
    }

    public function update(Redirect $redirect,RedirectUpdateRequest $request): bool
    {

        $data = $request->validated();
        DB::beginTransaction();
        try {
            $this->redirectRepository->update($redirect->id, $data);
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);
            return false;
        }

    }


    public function destroy($redirect)
    {
        return $redirect->delete();
    }

    public function changeStatus($id, $status): int
    {
        return $this->redirectRepository->update($id, ["status" => $status]);
    }


}
