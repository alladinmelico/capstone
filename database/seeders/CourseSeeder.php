<?php

namespace Database\Seeders;

use App\Models\Course;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $file_n = Storage::disk('local')->path('course-list.csv');
        $file = fopen($file_n, "r");
        $allData = array();
        while (($row = fgetcsv($file, 0, "\r")) !== false) {
            $allData = $row;
        }
        fclose($file);

        $rows = array();
        for ($i=0; $i < count($allData); $i++) {
            $data = explode(",", $allData[$i]);
            $rows[] = ['name' => trim($data[0]), 'code' => trim($data[1])];
        }
        Course::truncate();
        Course::insert($rows);
    }
}
