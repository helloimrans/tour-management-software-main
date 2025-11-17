<?php

namespace App\Http\Controllers;

use App\Services\TravelAgencyService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TravelAgencyController extends Controller
{
    protected TravelAgencyService $travelAgencyService;

    public function __construct(TravelAgencyService $travelAgencyService)
    {
        $this->travelAgencyService = $travelAgencyService;
    }

    public function showRegistrationForm()
    {
        return view('travel-agency.register');
    }

    public function register(Request $request)
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
                'password' => ['required', 'string', 'min:5', 'confirmed'],
                'address' => ['nullable', 'string', 'max:500'],
            ]);

            $user = $this->travelAgencyService->register($validatedData);

            return redirect()->route('login')->with([
                'message' => 'Registration successful! Please wait for admin approval.',
                'alert-type' => 'success',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Failed to register. Please try again.'])->withInput();
        }
    }

    public function dashboard()
    {
        $stats = $this->travelAgencyService->getDashboardStats(Auth::id());
        return view('travel-agency.dashboard', compact('stats'));
    }
}

