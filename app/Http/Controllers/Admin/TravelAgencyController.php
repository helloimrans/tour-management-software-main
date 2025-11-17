<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\TravelAgencyService;
use Illuminate\Http\Request;

class TravelAgencyController extends Controller
{
    protected TravelAgencyService $travelAgencyService;

    public function __construct(TravelAgencyService $travelAgencyService)
    {
        $this->travelAgencyService = $travelAgencyService;
    }

    public function index()
    {
        if (request()->ajax()) {
            return $this->travelAgencyService->datatable();
        }

        return view('admin.travel-agency.index');
    }

    public function create()
    {
        return view('admin.travel-agency.create');
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'first_name' => ['required', 'string', 'max:191'],
                'last_name' => ['nullable', 'string', 'max:191'],
                'company_name' => ['required', 'string', 'max:191'],
                'phone' => [
                    'required',
                    'regex:/^(01[3-9]\d{8})$/',
                    'unique:users,phone',
                ],
                'email' => [
                    'required',
                    'email',
                    'max:191',
                    'unique:users,email',
                ],
                'profile_pic' => [
                    'nullable',
                    'mimes:jpg,jpeg,png,webp,svg,gif',
                    'max:5120',
                ],
                'password' => ['required', 'string', 'min:5'],
                'address' => ['nullable', 'string', 'max:500'],
                'status' => ['required', 'in:0,1'],
            ]);

            $validatedData['status'] = (int) $validatedData['status'];
            $this->travelAgencyService->store($validatedData);

            return redirect()->route('travel.agency.index')->with([
                'message' => 'Travel agency created successfully.',
                'alert-type' => 'success',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Failed to create travel agency. Please try again.'])->withInput();
        }
    }

    public function edit(string $id)
    {
        $data['data'] = $this->travelAgencyService->show($id);
        return view('admin.travel-agency.edit', $data);
    }

    public function update(Request $request, string $id)
    {
        try {
            $validatedData = $request->validate([
                'first_name' => ['required', 'string', 'max:191'],
                'last_name' => ['nullable', 'string', 'max:191'],
                'company_name' => ['required', 'string', 'max:191'],
                'phone' => [
                    'required',
                    'regex:/^(01[3-9]\d{8})$/',
                    'unique:users,phone,' . $id,
                ],
                'email' => [
                    'required',
                    'email',
                    'max:191',
                    'unique:users,email,' . $id,
                ],
                'profile_pic' => [
                    'nullable',
                    'mimes:jpg,jpeg,png,webp,svg,gif',
                    'max:5120',
                ],
                'password' => ['nullable', 'string', 'min:5'],
                'address' => ['nullable', 'string', 'max:500'],
                'status' => ['required', 'in:0,1'],
            ]);

            $validatedData['status'] = (int) $validatedData['status'];
            $this->travelAgencyService->update($id, $validatedData);

            return redirect()->route('travel.agency.index')->with([
                'message' => 'Travel agency updated successfully.',
                'alert-type' => 'success',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Failed to update travel agency. Please try again.'])->withInput();
        }
    }

    public function destroy(string $id)
    {
        try {
            $this->travelAgencyService->delete($id);

            return redirect()->route('travel.agency.index')->with([
                'message' => 'Travel agency deleted successfully.',
                'alert-type' => 'success',
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Failed to delete travel agency. Please try again.']);
        }
    }
}

