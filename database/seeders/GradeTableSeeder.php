<?php

namespace Database\Seeders;

use App\Models\Grade;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class GradeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Grade::create(['name' => 'Kelas 1', 'grade' => '1']);
        Grade::create(['name' => 'Kelas 2', 'grade' => '2']);
        Grade::create(['name' => 'Kelas 3', 'grade' => '3']);
        Grade::create(['name' => 'Kelas 4', 'grade' => '4']);
        Grade::create(['name' => 'Kelas 5', 'grade' => '5']);
        Grade::create(['name' => 'Kelas 6', 'grade' => '6']);
    }
}
