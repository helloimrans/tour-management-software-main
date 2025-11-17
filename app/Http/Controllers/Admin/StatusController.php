<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StatusController extends Controller
{
    public function changeStatus(Request $request)
    {
        $id = $request->input('id');
        $table = $request->input('table');
        $column = $request->input('column');
        $status = $request->input('status');

        try {
            DB::table($table)
                ->where('id', $id)
                ->update([$column => $status]);

            return response()->json(['success' => true, 'message' => 'Status updated successfully.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}
