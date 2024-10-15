<?php

namespace App\Http\Controllers\Setting;

use App\Data\SettingData;
use App\Http\Controllers\Controller;
use App\Http\Requests\Setting\UpdateSettingRequest;
use App\Http\Resources\Setting\SettingResource;
use App\Services\Interfaces\ISettingService;
use Illuminate\Support\Facades\Auth;

class SettingController extends Controller
{
    protected readonly ISettingService $settingService;

    public function __construct(ISettingService $settingService)
    {
        $this->settingService = $settingService;
    }

    public function show()
    {
        $user = Auth::user();

        return SettingResource::make($user->setting);
    }

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
