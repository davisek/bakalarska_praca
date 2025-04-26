<?php

namespace App\Http\Controllers\Setting;

use App\Data\SettingData;
use App\Http\Controllers\Controller;
use App\Http\Requests\Setting\UpdateSettingRequest;
use App\Http\Resources\Setting\SettingResource;
use App\Services\Interfaces\ISettingService;
use Illuminate\Support\Facades\Auth;

/**
 * @OA\Tag(
 *     name="Settings",
 *     description="API Endpoints for managing user notification settings"
 * )
 */
class SettingController extends Controller
{
    protected readonly ISettingService $settingService;

    public function __construct(ISettingService $settingService)
    {
        $this->settingService = $settingService;
    }

    /**
     * Get user notification settings
     *
     * @OA\Get(
     *     path="/settings",
     *     operationId="getUserSettings",
     *     tags={"Settings"},
     *     summary="Get user notification settings",
     *     description="Returns the current user's notification settings",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/SettingResource")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthorized")
     * )
     */
    public function show()
    {
        $user = Auth::user();

        return SettingResource::collection($user->notificationSettings);
    }

    /**
     * Update user notification settings
     *
     * @OA\Put(
     *     path="/settings",
     *     operationId="updateUserSettings",
     *     tags={"Settings"},
     *     summary="Update user notification settings",
     *     description="Updates the current user's notification settings",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/UpdateSettingRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="type", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Settings updated successfully.")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=403, description="Forbidden - Email not verified"),
     *     @OA\Response(response=422, description="Validation error", @OA\JsonContent(ref="#/components/schemas/ValidationErrorResponse"))
     * )
     */
    public function update(UpdateSettingRequest $request)
    {
        $settingData = SettingData::from($request->validated());

        $this->settingService->update($settingData);

        return response()->json([
            'type' => 'success',
            'message' => trans('messages.settings_updated')
        ]);
    }
}