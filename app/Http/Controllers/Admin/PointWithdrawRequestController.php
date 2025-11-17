<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Point;
use App\Models\PointWithdrawRequest;
use App\Services\PointWithdrawRequestService;
use Illuminate\Http\Request;
use App\Services\RadioStationService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class PointWithdrawRequestController extends Controller
{
    protected $pointWithdrawRequestService;

    public function __construct(PointWithdrawRequestService $pointWithdrawRequestService)
    {
        $this->pointWithdrawRequestService = $pointWithdrawRequestService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (request()->ajax()) {
            return $this->pointWithdrawRequestService->datatable();
        }
        return view('admin.point-withdraw-request.index');
    }

    public function changeStatus(Request $request, string $id)
    {
        $data = PointWithdrawRequest::findOrFail($id);

        $data->update([
            'status' => $request->status,
            'approved_by' => Auth::id(),
        ]);

        if ($request->status == PointWithdrawRequest::APPROVE) {
            $point = Point::where('user_id', $data->user_id)->first();

            if ($point && $point->points >= $data->points) {
                $point->decrement('points', $data->points);
            } else {
                return redirect()->back()->with([
                    'message' => 'Insufficient points for withdrawal.',
                    'alert-type' => 'error',
                ]);
            }
        }

        return redirect()->back()->with([
            'message' => 'Status changed successfully.',
            'alert-type' => 'success',
        ]);
    }



    public function show($id){
        $data = PointWithdrawRequest::with('user', 'approvedBy')->find($id);
        return view('admin.point-withdraw-request.show', compact('data'));
    }


}
