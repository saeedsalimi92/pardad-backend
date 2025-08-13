<?php

namespace App\Services;

use App\Http\Requests\Admin\V1\Redirect\RedirectStoreRequest;
use App\Http\Requests\Admin\V1\Redirect\RedirectUpdateRequest;
use App\Http\Requests\Admin\V1\User\UserStoreRequest;
use App\Models\Redirect;
use App\Repositories\UserRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

readonly class UserService
{
    public function __construct(private readonly UserRepositoryInterface $userRepository)
    {
    }
    public function index($data, $perPage = 15): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        return $this->userRepository->index($data, $perPage);
    }
    public function findById($id, $with=[])
    {
        return $this->userRepository->findById($id, $with);
    }

    public function create(UserStoreRequest $request)
    {
        $data = $request->validated();

        DB::beginTransaction();
        try {
            $user = $this->userRepository->create($data);
            DB::commit();
            return $user;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);
            return null;
        }
    }

    public function update(Redirect $user,RedirectUpdateRequest $request): bool
    {

        $data = $request->validated();
        DB::beginTransaction();
        try {
            $this->userRepository->update($user->id, $data);
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);
            return false;
        }

    }


    public function destroy($user)
    {
        return $user->delete();
    }

    public function changeStatus($id, $status): int
    {
        return $this->userRepository->update($id, ["status" => $status]);
    }
    public function changePassword($id, $password): int
    {
        return $this->userRepository->update($id, ["password" => $password]);
    }
}
