<?php

namespace App\Repositories\RolePermission;

interface RoleInterface
{
    public function store(array $request);

    public function update(array $request, $data);
}