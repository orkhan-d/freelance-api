<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('roles')->insert([
            'id' => 0,
            'name' => 'Заказчик',
        ]);
        DB::table('roles')->insert([
            'id' => 1,
            'name' => 'Фрилансер',
        ]);
        DB::commit();
    }
}
