<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\FavouriteMusicResource;
use App\Http\Resources\MusicResource;
use App\Http\Resources\SingleMusicResource;
use App\Models\Music;
use App\Services\MusicService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class MusicController extends Controller
{
    use ApiResponse;

    protected $musicService;

    public function __construct(MusicService $musicService)
    {
        $this->musicService = $musicService;
    }


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $radioStationId = request('radio_station_id');
        try {
            $data = MusicResource::collection($this->musicService->getAll($radioStationId));
            return $this->ResponseSuccess($data, 'Data fetch successfully!');
        } catch (\Throwable $e) {
            return  $this->ResponseError($e->getMessage());
        }
    }

    //Get music by category id
    public function musicByCategory($musicCategoryId)
    {
        try {
            $data = MusicResource::collection($this->musicService->musicByCategory($musicCategoryId));
            return $this->ResponseSuccess($data, 'Data fetch successfully!');
        } catch (\Throwable $e) {
            return  $this->ResponseError($e->getMessage());
        }
    }

    public function storeFavouriteMusic(Request $request)
    {
        try {
            $validatedData = $this->musicService->favouriteMusicValidator($request->all());
            $data = $this->musicService->storeFavouriteMusic($validatedData);
            return $this->ResponseSuccess($data, 'Data inserted successfully!');
        } catch (ValidationException $exception) {
            return $this->ResponseError($exception->validator->errors());
        } catch (\Exception $e) {
            return  $this->ResponseError($e->getMessage());
        }
    }

    public function getFavouriteMusic()
    {

        $radioStationId = request('radio_station_id');
        $userId = request('user_id');

        try {
            $data = FavouriteMusicResource::collection($this->musicService->getFavouriteMusic($radioStationId, $userId));
            return $this->ResponseSuccess($data, 'Data fetch successfully!');
        } catch (\Throwable $e) {
            return  $this->ResponseError($e->getMessage());
        }
    }
    public function show($id)
    {
        try {
            $data = new SingleMusicResource((object) $this->musicService->show($id));
            return $this->ResponseSuccess($data, 'Data fetch successfully!');
        } catch (\Throwable $e) {
            return  $this->ResponseError($e->getMessage());
        }
    }
}
