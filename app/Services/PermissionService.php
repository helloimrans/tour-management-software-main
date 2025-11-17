<?php


namespace App\Services;


use App\Helpers\Classes\AuthHelper;
use App\Models\Permission;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class PermissionService
{
    public function deletePermission(Permission $permission): ?bool
    {
        return $permission->delete();
    }

    public function validator(array $postData, int $id = null): \Illuminate\Contracts\Validation\Validator
    {
        return Validator::make($postData, $this->validationRules($id));
    }

    public function validationRules(int $id = null): array
    {
        return [
            'name' => ['required', 'string', 'max:191', 'unique:permissions,name,' . $id],
            'display_name' => ['required', 'string', 'max:255'],
            'group_name' => ['required', 'string', 'max:255'],
        ];
    }

    public function createPermission(array $postData)
    {
        return Permission::create($postData);
    }

    public function updatePermission(Permission $permission, array $postData)
    {
        return $permission->update($postData);
    }

    public function getListDataForDatatable(Request $request): JsonResponse
    {
        $authUser = AuthHelper::getAuthUser();
        /** @var Builder $permissions */

        $permissions = Permission::select([
            'permissions.id as id',
            'permissions.name',
            'permissions.display_name',
            'permissions.created_at',
            'permissions.updated_at'
        ]);

        return DataTables::eloquent($permissions)
            ->addColumn('action', function (Permission $permission) use($authUser) {
                $str = '';

                if($authUser->hasPermission('permissions-read')) {
                    $str .= '<a href="' . route('permissions.show', $permission->id) . '" class="btn-action ba-primary"> <i class="fas fa-eye"></i> View </a>';
                }
                if($authUser->hasPermission('permissions-update')) {
                    $str .= '<a href="' . route('permissions.edit', $permission->id) . '" class="btn-action ba-warning"> <i class="fas fa-edit"></i> Edit </a>';
                }
                if($authUser->hasPermission('permissions-delete')) {
                    $str .= '<a href="#" data-action="' . route('permissions.destroy', $permission->id) . '" class="btn-action ba-danger delete"> <i class="fas fa-trash"></i> Delete</a>';
                }

                return $str;
            })
            ->rawColumns(['action'])
            ->toJson();
    }
}
