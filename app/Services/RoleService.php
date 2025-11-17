<?php

namespace App\Services;

use App\Helpers\Classes\AuthHelper;
use App\Models\Role;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class RoleService
{
    public function deleteRole(Role $role): ?bool
    {
        return $role->delete();
    }

    public function validator(array $postData, int $id = null): \Illuminate\Contracts\Validation\Validator
    {
        $rules = [
            'display_name' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:191', 'unique:roles,name,'.$id],
            'description' => 'string',
        ];

        return Validator::make($postData, $rules);
    }

    public function createRole(array $postData)
    {
        return Role::create($postData);
    }

    public function updateRole(Role $role, array $postData)
    {
        return $role->update($postData);
    }

    public function getListDataForDatatable(Request $request): JsonResponse
    {
        $authUser = AuthHelper::getAuthUser();
        /** @var Builder $roles */

        $roles = Role::select([
            'roles.id as id',
            'roles.name',
            'roles.display_name',
            'roles.description',
            'roles.created_at',
            'roles.updated_at',
        ]);

        return DataTables::eloquent($roles)
            ->editColumn('description', static function (Role $role) {
                return Str::limit($role->description, 20, '...');
            })
            ->addColumn('action', function (Role $role) use ($authUser) {
                $str = '';
                if($authUser->hasPermission('roles-change-permission')) {
                    $str .= '<a href="'.route('roles.permissions', $role->id).'" class="btn-action ba-primary"> <i class="fas fa-cogs"></i> Permissions</a>';
                }
                if($authUser->hasPermission('roles-read')) {
                    $str .= '<a href="'.route('roles.show', $role->id).'" class="btn-action ba-info"> <i class="fas fa-eye"></i> View</a>';
                }
                if($authUser->hasPermission('roles-update')) {
                    $str .= '<a href="'.route('roles.edit', $role->id).'" class="btn-action ba-warning"> <i class="fas fa-edit"></i> Edit </a>';
                }
                if($authUser->hasPermission('roles-delete')) {
                    $str .= '<a href="#" data-action="'.route('roles.destroy', $role->id).'" class="btn-action ba-danger delete"> <i class="fas fa-trash"></i> Delete</a>';
                }

                return $str;
            })
            ->rawColumns(['action'])
            ->toJson();
    }

    public function syncRolePermission(Role $role, array $permissions): Role
    {
        $role->permissions()->sync($permissions);

        /** TODO: not a good idea */
        $role->users()->pluck('id')->each(function ($userId) {
            Cache::forget('userwise_permissions_'.$userId);
        });

        return $role;
    }
}
