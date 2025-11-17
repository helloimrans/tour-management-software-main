<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TotalViewer;
use App\Services\ViewersService;
use Illuminate\Http\Request;

class ViewersController extends Controller
{

    protected ViewersService $viewersService;

    public function __construct(ViewersService $viewersService)
    {
        $this->viewersService = $viewersService;
    }

    public function index()
    {
        $data['viewers'] = $this->viewersService->getViewers();
        return view('admin.viewer.index', $data);
    }

    public function update(Request $request)
    {
        $validatedData = $this->viewersService->validator($request->all());
        try {
            $this->viewersService->update($validatedData);
            return redirect()->back()->with([
                'message' => 'Data updated successfully.',
                'alert-type' => 'success',
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }
}
