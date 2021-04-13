<?php

namespace App\Services\Permission\Traits;

use Illuminate\Support\Arr;
use App\Models\Role;

trait HasRoles
{

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function giveRolesTo(...$roles)
    {
        $roles = $this->getAllRoles($roles);
        
        $this->roles()->sync($roles);
    }

    protected function getAllRoles(array $roles)
    {
        return Role::whereIn('name',Arr::flatten($roles))->get();
    }
    
    public function hasRole(string $role)
    {
        return $this->roles->contains('name',$role);
    }
}