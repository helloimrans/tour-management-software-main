<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\LiveRadioCommentService;




class LiveRadioController extends Controller
{

    protected LiveRadioCommentService $liveRadioCommentService;

    public function __construct(LiveRadioCommentService $liveRadioCommentService)
    {
        $this->liveRadioCommentService = $liveRadioCommentService;
    }

    public function index()
    {
        if (request()->ajax()) {
            return $this->liveRadioCommentService->datatable(); // Ensure this returns a JSON response
        }
        return view('admin.live-comments.index');
    }




    public function destroy(int $id)
    {

        try {
            $data['data'] = $this->liveRadioCommentService->delete($id);
            return redirect()->route('admin.live-comments.index')->with([
                'message' => 'Data Delete successfully.',
                'alert-type' => 'success',
            ]);
        } catch (\Throwable $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }
}
