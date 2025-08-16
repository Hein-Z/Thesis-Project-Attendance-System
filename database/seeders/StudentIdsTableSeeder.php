<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class StudentIdsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $students = [
            ['student_id' => 'S001', 'name' => 'Kelvin'],
            ['student_id' => 'S002', 'name' => 'Joe'],
            ['student_id' => 'S003', 'name' => 'Kai'],
            ['student_id' => 'S004', 'name' => 'Max'],
            ['student_id' => 'S005', 'name' => 'Sakura'],
            ['student_id' => 'S006', 'name' => 'Hitana'],
            ['student_id' => 'S007', 'name' => 'Noah'],
            ['student_id' => 'S008', 'name' => 'Elle'],
            ['student_id' => 'S009', 'name' => 'Mg Sai'],
            ['student_id' => 'S010', 'name' => 'Mg Hein'],
            ['student_id' => 'S011', 'name' => 'Ag Ag'],
            ['student_id' => 'S012', 'name' => 'Mikey'],
            ['student_id' => 'S013', 'name' => 'Luffy'],
            ['student_id' => 'S014', 'name' => 'Zoro'],
            ['student_id' => 'S015', 'name' => 'Senji'],
            ['student_id' => 'S016', 'name' => 'Mike'],
            ['student_id' => 'S017', 'name' => 'Bookie'],
        ];

        DB::table('student_ids')->insert($students);
    }
}
