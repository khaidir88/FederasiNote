<?php

namespace App\Traits;

use App\Models\Role;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

trait HasRoles
{
    /**
     * Relasi ke tabel roles (many-to-many)
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class)->withTimestamps();
    }

    /**
     * Cek apakah user punya role tertentu
     *
     * @param  string|array  $roles
     * @return bool
     */
    public function hasRole($roles): bool
    {
        if (is_array($roles)) {
            return $this->roles()->whereIn('name', $roles)->exists();
        }

        return $this->roles()->where('name', $roles)->exists();
    }

    /**
     * Cek apakah user punya salah satu role dari daftar
     *
     * @param  array  $roles
     * @return bool
     */
    public function hasAnyRole(array $roles): bool
    {
        return $this->roles()->whereIn('name', $roles)->exists();
    }

    /**
     * Tambahkan role ke user
     *
     * @param  string|\App\Models\Role  $role
     * @return void
     */
    public function assignRole($role): void
    {
        $roleModel = $role instanceof Role
            ? $role
            : Role::where('name', $role)->first();

        if ($roleModel) {
            $this->roles()->syncWithoutDetaching([$roleModel->id]);
        }
    }

    /**
     * Hapus role dari user
     *
     * @param  string|\App\Models\Role  $role
     * @return void
     */
    public function removeRole($role): void
    {
        $roleModel = $role instanceof Role
            ? $role
            : Role::where('name', $role)->first();

        if ($roleModel) {
            $this->roles()->detach($roleModel->id);
        }
    }

    /**
     * Cek apakah user memiliki role admin
     *
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->hasRole('Admin') || $this->hasRole('Super Admin');
    }
}
