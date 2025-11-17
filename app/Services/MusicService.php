<?php

namespace App\Services;

use App\Models\FavouriteMusic;
use App\Models\Music;
use App\Models\MusicCategory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Mockery\Exception;
use Yajra\DataTables\Facades\DataTables;

class MusicService
{
    public function validator(array $data, $id = null): array
    {
        $rules = [
            'title' => ['required', 'string', 'max:191', 'unique:music,title,' . $id],
            'description' => ['nullable', 'string'],
            'music_file' => ['nullable', 'mimes:mp3,wav,ogg'],
            'music_author' => ['nullable', 'string'],
            'music_views' => ['nullable', 'integer'],
            'thumbnail_image' => ['nullable', 'mimes:jpg,jpeg,png,webp,svg,gif', 'max:5120'],
            'radio_station_id' => ['nullable', 'exists:radio_stations,id'],
            'music_category_id' => ['nullable', 'exists:music_categories,id'],
            'category_ids' => 'required|array',
            'category_ids.*' => 'exists:music_categories,id',
        ];

        return Validator::make($data, $rules)->validate();
    }

    public function favouriteMusicValidator(array $data, $id = null): array
    {
        $rules = [
            'radio_station_id' => ['required', 'exists:radio_stations,id'],
            'user_id' => ['required', 'exists:users,id'],
            'music_id' => ['required', 'exists:music,id'],
        ];

        return Validator::make($data, $rules)->validate();
    }

    public function getAll(int $radioStationId)
    {
        return Music::where('radio_station_id', $radioStationId)
            ->with(['createdBy', 'updatedBy', 'musicCategories:id,name,slug'])
            ->latest()
            ->get();
    }

    public function getFavouriteMusic(?int $radioStationId = null, ?int $userId = null)
    {
        return FavouriteMusic::select(['id', 'radio_station_id', 'user_id', 'music_id'])
            ->with('musics')
            ->where(['radio_station_id' => $radioStationId, 'user_id' => $userId])
            ->latest()
            ->get();
    }

    public function musicByCategory(int $musicCategoryId)
    {
        $musics =  MusicCategory::with('musics')
            ->findOrFail($musicCategoryId)
            ->musics;

            dd($musics);
    }
    public function store(array $input): Music
    {
        return DB::transaction(function () use ($input) {
            if (isset($input['music_file'])) {
                $input['music_file'] = uploadFile($input['music_file'], 'musics');
            }
            if (isset($input['thumbnail_image'])) {
                $input['thumbnail_image'] = uploadFile($input['thumbnail_image'], 'music_thumbnail_image');
            }

            $input['slug'] = Str::slug($input['title']);
            $input['created_by'] = Auth::user()->id ?? null;

            $music = Music::create($input);

            if (isset($input['category_ids']) && is_array($input['category_ids'])) {
                $music->musicCategories()->sync($input['category_ids']);
            }

            return $music;
        });
    }


    public function storeFavouriteMusic(array $input): FavouriteMusic
    {
        return FavouriteMusic::create($input);
    }


    public function show(int $id): Music
    {
        return Music::with('musicCategory', 'radioStation', 'musicCategories')->findOrFail($id);
    }

    public function update(int $id, array $input): Music
    {
        try {
            $data = Music::findOrFail($id);

            if (isset($input['music_file'])) {
                if ($data->music_file) {
                    deleteFile($data->music_file);
                }
                $input['music_file'] = uploadFile($input['music_file'], 'musics');
            }

            if (isset($input['thumbnail_image'])) {
                if ($data->thumbnail_image) {
                    deleteFile($data->thumbnail_image);
                }
                $input['thumbnail_image'] = uploadFile($input['thumbnail_image'], 'music_thumbnail_image');
            }

            $input['slug'] = Str::slug($input['title']);
            $input['updated_by'] = Auth::user()->id ?? null;

            $data->update($input);

            if (isset($input['category_ids']) && is_array($input['category_ids'])) {
                $data->musicCategories()->sync($input['category_ids']);
            }

            return $data;
        } catch (Exception $e) {
            Log::error('Failed to update music: ' . $e->getMessage());
            throw new \Exception('There was an error updating the music. Please try again.');
        }
    }


    public function delete(int $id)
    {
        $data = Music::find($id);
        $data->deleted_by = Auth::user()->id ?? null;
        $data->save();
        $data->delete();
        return $data;
    }

    public function datatable(int $radioStationId = null)
    {
        $data = Music::with(['createdBy', 'updatedBy', 'radioStation', 'musicCategory', 'musicCategories'])
            ->when($radioStationId, function ($query) use ($radioStationId) {
                $query->where('radio_station_id', $radioStationId);
            })
            ->forRadioStation()
            ->latest();

        //Filter by radio_station_id
        if (request()->has('radio_station_id') && !empty(request()->radio_station_id)) {
            $data->where('radio_station_id', request()->radio_station_id);
        }

        return DataTables::of($data)
            ->addColumn('created_by_name', function ($row) {
                return $row->createdBy->first_name ?? 'N/A';
            })
            ->addColumn('updated_by_name', function ($row) {
                return $row->updatedBy->first_name ?? 'N/A';
            })
            ->addColumn('music_category_name', function ($row) {
                return $row->musicCategory->name ?? 'N/A';
            })
            ->addColumn('music_categories', function ($row) {
                if ($row->musicCategories && $row->musicCategories->count() > 0) {
                    return $row->musicCategories->map(function ($category) {
                        return '<span class="badge badge-primary">' . htmlspecialchars($category->name) . '</span>';
                    })->join(' ');
                }
                return '<span class="badge badge-secondary">N/A</span>';
            })
            ->addColumn('radio_station_name', function ($row) {
                return $row->radioStation->name ?? 'N/A';
            })
            ->editColumn('music_file', function ($row) {
                $musicfileurl = $row->music_file ? Storage::url($row->music_file) : asset('defaults/noimage/no_img.jpg');
                $musicFile = '<audio controls>
                    <source src="' . $musicfileurl . '" type="audio/mpeg">
                </audio>';
                return $musicFile;
            })
            ->editColumn('thumbnail_image', function ($row) {
                $imageUrl = $row->thumbnail_image ? Storage::url($row->thumbnail_image) : '';
                $thumbnail_image = '<img src= "' . $imageUrl . '" alt="' . $row->name . '" width="70">';
                return $thumbnail_image;
            })
            ->editColumn('is_active', function ($row) {
                $checkStatus = $row->is_active ? 'checked' : '';
                return '<div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input change-status-checkbox" id="customSwitch' . $row->id . '" data-id="' . $row->id . '" ' . $checkStatus . '>
                            <label class="custom-control-label" for="customSwitch' . $row->id . '"></label>
                         </div>';
            })

            ->addColumn('action', function ($row) {
                $str = '<a href="' . route('music.edit', $row->id) . '" class="btn bg-gradient-primary btn-xs mx-1" data-id="' . $row->id . '"><i
                class="fas fa-edit"></i> EDIT</a>';
                $deleteUrl = route('music.destroy', $row->id);
                $formId = 'delForm-' . $row->id;

                $str .= '<form class="d-inline" id="' . $formId . '" action="' . $deleteUrl . '" method="POST">' .
                    csrf_field() .
                    method_field("DELETE") .
                    '<button type="button" class="btn bg-gradient-danger btn-xs mx-1" onclick="confirmDelete(\'' . $formId . '\')"><i class="far fa-trash-alt"></i> Delete</button>' .
                    '</form>';
                return $str;
            })
            ->rawColumns(['action', 'is_active', 'music_file', 'thumbnail_image', 'created_by_name', 'updated_by_name'. 'music_category_name', 'radio_station_name', 'music_categories'])
            ->make(true);
    }
}
