<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;

class RoleAdminTableSeeder extends Seeder
{
    public function run()
    {
        Admin::findOrFail(1)->roles()->sync(1);
    }
}
