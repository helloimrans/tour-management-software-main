<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\LiveCommentService;
use App\Services\RadioStationService;

class LiveCommentController extends Controller
{
    protected LiveCommentService $commentService;
    protected $radioStationService;

    public function __construct(LiveCommentService $commentService,  RadioStationService $radioStationService)
    {
        $this->commentService = $commentService;
        $this->radioStationService = $radioStationService;
    }

    public function index()
    {
        if (request()->ajax()) {
            return $this->commentService->datatable();
        }
        $data['radioStations'] =  $this->radioStationService->getAll();
        return view('admin.live-comment.index', $data);
    }
}
