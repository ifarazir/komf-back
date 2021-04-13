<?php

namespace App\Services\Permission\Traits;

use App\Models\Permission;
use Illuminate\Support\Arr;

trait HasPermissions
{
    public function permissions()
    {
        return $this->BelongsToMany(Permission::class);
    }

    public function givePermissionsTo(...$permissions)
    {
        $permissions = $this->getAllPermissions($permissions);

        $this->permissions()->sync($permissions);

        return $this;
    }

    protected function getAllPermissions(array $permissions)
    {

        return Permission::whereIn('name', Arr::flatten($permissions))->get();
    }

    public function hasPermission(Permission $permission)
    {
        return $this->hasPermissionsThroughRole($permission) || $this->permissions->contains($permission);
    }

    protected function hasPermissionsThroughRole(Permission $permission)
    {
        foreach ($permission->roles as $role) {
            if ($this->roles->contains($role)) return true;
        }

        return false;
    }
}
