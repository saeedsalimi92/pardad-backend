<?php

namespace App\Services;


use App\Http\Requests\Admin\V1\User\UserUpdateRequest;
use App\Http\Requests\Admin\V1\User\UserStoreRequest;
use App\Models\User;
use App\Repositories\UserRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
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
        $data['password'] = Hash::make($data['password']);

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

    public function update(User $user,UserUpdateRequest $request): bool
    {

        $data = $request->validated();

        Log::info($data);


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
        $newPassword = Hash::make($password);
        return $this->userRepository->update($id, ["password" => $newPassword]);
    }
}
