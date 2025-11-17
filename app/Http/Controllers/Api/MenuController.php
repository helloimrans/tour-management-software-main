<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Traits\ApiResponse;


class MenuController extends Controller

{
    use ApiResponse;

    public function index()
    {
        try {
            $data = Menu::select(['id','url','name'])->where('is_active',1)->get();
            return $this->ResponseSuccess($data, 'Data fetch successfully!');
        } catch (\Throwable $e) {
            return  $this->ResponseError($e->getMessage());
        }
    }
}
