<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\SettingsService;
use Illuminate\Http\Request;

class SettingsController extends Controller
{

    protected SettingsService $settingsService;

    public function __construct(SettingsService $settingsService)
    {
        $this->settingsService = $settingsService;
    }

    public function index()
    {
        $data['setting'] = $this->settingsService->getSettings();
        return view('admin.setting.index', $data);
    }

    public function update(Request $request)
    {
        $validatedData = $this->settingsService->validator($request->all());
        try {
            $this->settingsService->update($validatedData);
            return redirect()->back()->with([
                'message' => 'Data updated successfully.',
                'alert-type' => 'success',
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }
}
