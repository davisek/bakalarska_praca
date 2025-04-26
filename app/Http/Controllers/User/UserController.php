<?php

namespace App\Http\Controllers\User;

use App\Data\ChangePasswordData;
use App\Data\UserData;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\UpdatePasswordRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Http\Requests\User\UserSearchRequestQuery;
use App\Http\Resources\User\StatisticsResource;
use App\Http\Resources\User\UserResource;
use App\Models\User;
use App\Services\Interfaces\IUserService;
use Illuminate\Support\Facades\Auth;

/**
 * @OA\Tag(
 *     name="Users",
 *     description="API Endpoints for managing users"
 * )
 */
class UserController extends Controller
{
    protected readonly IUserService $userService;

    public function __construct(IUserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Get users list
     *
     * @OA\Get(
     *     path="/users",
     *     operationId="getUsers",
     *     tags={"Users"},
     *     summary="Get users list",
     *     description="Returns a paginated list of users (admin only)",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(ref="#/components/parameters/PageParameter"),
     *     @OA\Parameter(ref="#/components/parameters/PerPageParameter"),
     *     @OA\Parameter(ref="#/components/parameters/SearchParameter"),
     *     @OA\Parameter(
     *         name="sort_by",
     *         in="query",
     *         description="Field to sort by",
     *         required=false,
     *         @OA\Schema(type="string", enum={"name", "surname", "email", "email_verified_at", "is_admin", "created_at"})
     *     ),
     *     @OA\Parameter(ref="#/components/parameters/SortDirParameter"),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/UserResource")
     *             ),
     *             @OA\Property(
     *                 property="links",
     *                 type="object",
     *                 @OA\Property(property="first", type="string"),
     *                 @OA\Property(property="last", type="string"),
     *                 @OA\Property(property="prev", type="string", nullable=true),
     *                 @OA\Property(property="next", type="string", nullable=true)
     *             ),
     *             @OA\Property(
     *                 property="meta",
     *                 type="object",
     *                 @OA\Property(property="current_page", type="integer"),
     *                 @OA\Property(property="from", type="integer"),
     *                 @OA\Property(property="last_page", type="integer"),
     *                 @OA\Property(property="path", type="string"),
     *                 @OA\Property(property="per_page", type="integer"),
     *                 @OA\Property(property="to", type="integer"),
     *                 @OA\Property(property="total", type="integer")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=403, description="Forbidden - Admin access required")
     * )
     */
    public function index(UserSearchRequestQuery $request)
    {
        $users = $this->userService->index($request->validated());

        return UserResource::collection($users);
    }

    /**
     * Get current user details
     *
     * @OA\Get(
     *     path="/user",
     *     operationId="getCurrentUser",
     *     tags={"Users"},
     *     summary="Get current user details",
     *     description="Returns the authenticated user's details",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/UserResource")
     *     ),
     *     @OA\Response(response=401, description="Unauthorized")
     * )
     */
    public function show()
    {
        $user = Auth::user();

        return UserResource::make($user);
    }

    /**
     * Update user profile
     *
     * @OA\Put(
     *     path="/user",
     *     operationId="updateUser",
     *     tags={"Users"},
     *     summary="Update user profile",
     *     description="Updates the authenticated user's profile information",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/UpdateUserRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="type", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Profile updated successfully.")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=422, description="Validation error", @OA\JsonContent(ref="#/components/schemas/ValidationErrorResponse"))
     * )
     */
    public function update(UpdateUserRequest $request)
    {
        $userData = UserData::from($request->validated());

        $this->userService->update($userData);

        return response()->json([
            'type' => 'success',
            'message' => trans('messages.profile_updated_successfully'),
        ]);
    }

    /**
     * Change user password
     *
     * @OA\Put(
     *     path="/user/change-password",
     *     operationId="changePassword",
     *     tags={"Users"},
     *     summary="Change user password",
     *     description="Changes the authenticated user's password",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/UpdatePasswordRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="type", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Your password has been updated successfully.")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(
     *         response=422,
     *         description="Password validation failed",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="type", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="The current password does not match your current password."),
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 @OA\Property(
     *                     property="current_password",
     *                     type="array",
     *                     @OA\Items(type="string", example="The current password does not match your current password.")
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function changePassword(UpdatePasswordRequest $request)
    {
        $changePasswordData = ChangePasswordData::from($request->validated());

        $response = $this->userService->updatePassword($changePasswordData);

        return $response;
    }

    /**
     * Get system statistics
     *
     * @OA\Get(
     *     path="/admin/statistics",
     *     operationId="getStatistics",
     *     tags={"Users"},
     *     summary="Get system statistics",
     *     description="Returns system statistics (admin only)",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/StatisticsResource")
     *     ),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=403, description="Forbidden - Admin access required")
     * )
     */
    public function getStatistics()
    {
        $data = $this->userService->getStatistics();

        return StatisticsResource::make($data);
    }

    /**
     * Delete user
     *
     * @OA\Delete(
     *     path="/users/{userId}",
     *     operationId="deleteUser",
     *     tags={"Users"},
     *     summary="Delete user",
     *     description="Deletes a user from the system (admin only)",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="userId",
     *         in="path",
     *         required=true,
     *         description="ID of the user to delete",
     *         @OA\Schema(type="integer", format="int64")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/SuccessResponse")
     *     ),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=403, description="Forbidden - Admin access required"),
     *     @OA\Response(response=404, description="User not found")
     * )
     */
    public function delete(int $userId)
    {
        User::findOrFail($userId)->delete();

        return response()->json([
            'type' => 'success',
            'message' => trans('messages.deleted_successfully'),
        ]);
    }

    /**
     * Generate admin authentication key
     *
     * @OA\Post(
     *     path="/admin",
     *     operationId="generateAuthKey",
     *     tags={"Users"},
     *     summary="Generate admin authentication key",
     *     description="Generates an authentication key for admin actions (admin only)",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="type", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Created successfully"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="auth_key", type="string", description="Generated authentication key")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=403, description="Forbidden - Admin access required")
     * )
     */
    public function generateAuthKey()
    {
        $data = $this->userService->generateAuthKey();

        return response()->json([
            'type' => 'success',
            'message' => trans('messages.created_successfully'),
            'data' => [
                'auth_key' => $data
            ]
        ]);
    }
}