<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    private $permissions = [
        'read-companies',
        'create-companies',
        'update-companies',
        'delete-companies',
        'read-employees',
        'create-employees',
        'update-employees',
        'delete-employees',
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach ($this->permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }
    }
}
