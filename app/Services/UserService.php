<?php
namespace App\Services;

use App\Data\ChangePasswordData;
use App\Data\UserData;
use App\Models\Measurement;
use App\Models\Sensor;
use App\Models\User;
use App\Services\Interfaces\IAuthService;
use App\Services\Interfaces\IUserService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserService implements IUserService
{
    const PER_PAGE = 15;

    protected readonly IAuthService $authService;

    public function __construct(IAuthService $authService)
    {
        $this->authService = $authService;
    }

    public function index(array $request)
    {
        $perPage = $request['per_page'] ?? self::PER_PAGE;

        $query = User::query();

        if (!empty($request['search'])) {
            $search = '%' . strtolower($request['search']) . '%';
            $query->where(function($q) use ($search) {
                $q->whereRaw('LOWER(name) LIKE ?', [$search])
                    ->orWhereRaw('LOWER(surname) LIKE ?', [$search])
                    ->orWhereRaw('LOWER(email) LIKE ?', [$search]);
            });
        }

        $sortBy = $request['sort_by'] ?: 'name';
        $sortDir = $request['sort_dir'] ?: 'asc';

        $query->orderBy($sortBy, $sortDir);

        return $query->paginate($perPage);
    }

    public function update(UserData $userData)
    {
        $user = Auth::user();

        $isMailNew = $user->email !== $userData->email;

        if ($isMailNew) {
            $user->email_verified_at = null;
            $user->save();
        }

        $user->update([
            'name' => $userData->name,
            'surname' => $userData->surname,
            'email' => $userData->email,
            'locale' => $userData->locale,
        ]);

        if ($isMailNew) {
            $this->authService->resendVerificationCode();
        }
    }

    public function updatePassword(ChangePasswordData $changePasswordData)
    {
        $user = Auth::user();

        if (!Hash::check($changePasswordData->current_password, $user->password)) {
            return response()->json([
                'type' => 'error',
                'message' => trans('errors.current_password_incorrect'),
                'errors' => [
                    'current_password' => [trans('errors.current_password_incorrect')]
                ]
            ], 422);
        }

        $user->update([
            'password' => $changePasswordData->password,
        ]);

        return response()->json([
            'type' => 'success',
            'message' => trans('messages.password_updated_successfully'),
        ]);
    }

    public function getStatistics()
    {
        $today = Carbon::today();

        $users = User::all();

        return [
            'totalUsers' => $users->count(),
            'adminUsers' => $users->where('is_admin', true)->count(),
            'totalSensors' => Sensor::all()->count(),
            'newUsersToday' => User::whereDate('created_at', $today)->count(),
            'newReadingsToday' => Measurement::whereDate('created_at', $today)->count(),
        ];
    }
}
