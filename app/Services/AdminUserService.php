<?php



namespace App\Services;

use App\Helpers\Classes\AuthHelper;
use App\Models\FavouriteMusic;
use App\Models\Music;
use App\Models\MusicCategory;
use App\Models\Service;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class AdminUserService
{
    public function validator(array $data, $id = null)
    {
        $rules = [
            'first_name' => 'required|max:191',
            'last_name' => 'nullable|string|max:191',
            'phone' => 'required|regex:/^(01[3-9]\d{8})$/|unique:users,phone,' . $id,
            'email' => 'required|email|unique:users,email,' . $id,
            'profile_pic' => 'nullable|mimes:jpg,jpeg,png,webp,svg,gif|max:5120',
            'password' => $id ? 'nullable|min:5' : 'required|min:5',
            'radio_station_id' => ['nullable', 'integer'],
            'role_id' => [
                'bail',
                'required',
            ],
        ];

        return Validator::make($data, $rules)->validate();
    }

    public function getAll()
    {
        return User::with(['createdBy', 'updatedBy'])->where('user_type', User::ADMIN_USER_CODE)->latest()->get();
    }



    public function store($input)
    {
        $roles = $input['role_id'];

        if (isset($input['profile_pic'])) {
            $input['profile_pic'] = $this->storeFile($input['profile_pic'], 'profile_pic');
        }
        $input['created_by'] = Auth::user()->id ?? null;
        $input['user_type'] = User::ADMIN_USER_CODE;
        $user =  User::create($input);
        $user->syncRoles($roles);
    }


    public function show($id)
    {
        $data = User::where('user_type', User::ADMIN_USER_CODE)->findOrFail($id);
        return $data;
    }

    public function update($id, $input)
    {
        $roles = $input['role_id'];

        $data = User::find($id);
        $input['updated_by'] = Auth::user()->id ?? null;
        if (!isset($input['password'])) {
            unset($input['password']);
        }
        if (isset($input['profile_pic'])) {
            if ($data->profile_pic) {
                deleteFile($data['profile_pic']);
            }
            $input['profile_pic'] = uploadFile($input['profile_pic'], 'profile_pic');
        }

        $data->update($input);

        $userRoles = $data->roles()->pluck('name')->toArray();
        $data->removeRoles($userRoles);
        $data->syncRoles($roles);
        return $data;
    }

    public function delete($id)
    {
        $data = User::find($id);
        $data->deleted_by = Auth::user()->id ?? null;
        $data->save();
        $data->delete();
        return true;
    }

    public function datatable()
    {
        $authUser = AuthHelper::getAuthUser();

        $data = User::with(['createdBy', 'updatedBy', 'radioStation:id,name'])->adminUser()->latest();
        return DataTables::of($data)
            ->addColumn('created_by_name', function ($row) {
                return $row->createdBy->name;
            })
            ->addColumn('updated_by_name', function ($row) {
                return $row->updatedBy->name;
            })
            ->addColumn('radio_station', function ($row) {
                return $row->radioStation->name ?? 'All Radio Station';
            })
            ->addColumn('roles', function ($user) {
                return $user->roles->pluck('display_name')->implode(', ');
            })
            ->editColumn('profile_pic', function ($row) {
                $imageUrl = $row->profile_pic ? Storage::url($row->profile_pic) : asset('defaults/noimage/no_img.jpg');
                $profilePic = '<img src= "' . $imageUrl . '" alt="' . $row->name . '" width="70">';
                return $profilePic;
            })
            ->editColumn('status', function ($row) use ($authUser) {
                $checkStatus = $row->status ? 'checked' : '';
                $status =  '<div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input change-status-checkbox" id="customSwitch' . $row->id . '" data-id="' . $row->id . '" ' . $checkStatus . '>
                            <label class="custom-control-label" for="customSwitch' . $row->id . '"></label>
                         </div>';
                if ($authUser->hasPermission('admin-user-change-status')) {
                    return $status;
                }
            })
            ->addColumn('action', function ($row) use ($authUser) {
                $str = '';
                $editRoute = route('admin.user.edit', $row->id);
                $deleteUrl = route('admin.user.destroy', $row->id);

                $formId = 'delForm-' . $row->id;
                if ($authUser->hasPermission('admin-user-update')) {
                    $str .= '<a href="' . $editRoute . '" class="btn bg-gradient-primary btn-xs mx-1"><i class="fas fa-edit"></i> EDIT</a>';
                }

                if ($authUser->hasPermission('admin-user-delete')) {
                    $str .= '<form class="d-inline" id="' . $formId . '" action="' . $deleteUrl . '" method="POST">' .
                        csrf_field() .
                        method_field("DELETE") .
                        '<button type="button" class="btn bg-gradient-danger btn-xs mx-1" onclick="confirmDelete(\'' . $formId . '\')"><i class="far fa-trash-alt"></i> Delete</button>' .
                        '</form>';
                }

                return $str;
            })
            ->rawColumns(['action', 'status', 'created_by_name', 'updated_by_name', 'profile_pic', 'radio_station'])
            ->make(true);
    }
    private function storeFile($file, $directory): string
    {
        return $file->store($directory, 'public');
    }

    private function deleteFile($filePath): void
    {
        Storage::delete($filePath);
    }
}
