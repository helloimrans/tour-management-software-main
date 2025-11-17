<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\MemberManagementService;
use Illuminate\Http\Request;

class MemberManagementController extends Controller
{
    protected MemberManagementService $memberManagementService;

    public function __construct(MemberManagementService $memberManagementService)
    {
        $this->memberManagementService = $memberManagementService;
    }

    public function index()
    {
        if (request()->ajax()) {
            return $this->memberManagementService->datatable();
        }

        return view('admin.member-management.index');
    }
}

