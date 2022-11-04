<?php

namespace App\Repositories\RolePermission;

use Spatie\Permission\Models\Permission;

class PermissionRepository implements PermissionInterface
{
    public function store($request)
    {
        return Permission::create($request);
    }

    public function update($request, $data)
    {
        $data->update($request->all());
        return $data;
    }
}