<?php

declare(strict_types=1);

namespace App\Models\Traits;

use App\Models\Role;
use Illuminate\Support\Collection;

trait HasPermissions
{
    public function can(string $permission): bool
    {
        return $this->allPermissions()->contains($permission);
    }

    public function hasRole(string $name): bool
    {
        return $this->roles->contains('name', $name);
    }

    public function allPermissions(): Collection
    {
        $permissions = collect();

        foreach ($this->roles as $role) {
            $permissions = $permissions->merge($role->permissions);
        }

        // If no roles, default to student permissions
        if ($permissions->isEmpty()) {
            $studentRole = Role::where('name', 'student')->first();
            if ($studentRole) {
                $permissions = collect($studentRole->permissions);
            }
        }

        return $permissions->unique();
    }

    public function flushPermissionCache(): void
    {
        // In a real app, this would clear cache, but for now, do nothing
    }
}
