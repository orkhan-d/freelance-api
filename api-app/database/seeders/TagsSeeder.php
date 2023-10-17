<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TagsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tags')->insert([
            'id'=>1,
            'name'=>'Front-End'
        ]);
        DB::table('tags')->insert([
            'id'=>2,
            'name'=>'Back-End'
        ]);
        DB::table('tags')->insert([
            'id'=>3,
            'name'=>'Laravel'
        ]);
        DB::table('tags')->insert([
            'id'=>4,
            'name'=>'JS'
        ]);
        DB::table('tags')->insert([
            'id'=>5,
            'name'=>'Telegram-bots'
        ]);
    }
}
