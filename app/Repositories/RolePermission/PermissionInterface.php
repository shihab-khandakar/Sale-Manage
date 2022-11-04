<?php

namespace App\Repositories\RolePermission;

interface PermissionInterface
{
    public function store(array $request);

    public function update(array $request, $data);
}