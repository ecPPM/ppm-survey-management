<?php

namespace Database\Seeders;

use App\Models\Survey;
use Illuminate\Database\Seeder;

class SurveySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Survey::create([
            'title' => 'University Survey',
            'start_date' => '2024-12-15',
            'end_date' => '2024-12-31',
            'status' => 'Preparation',
        ]);
    }
}
