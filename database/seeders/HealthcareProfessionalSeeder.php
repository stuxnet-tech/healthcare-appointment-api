<?php

namespace Database\Seeders;

use App\Models\HealthcareProfessional;
use Illuminate\Database\Seeder;

class HealthcareProfessionalSeeder extends Seeder
{
    public function run()
    {
        $professionals = [
            ['name' => 'Dr. Smith', 'specialty' => 'Cardiology'],
            ['name' => 'Dr. Johnson', 'specialty' => 'Dermatology'],
            ['name' => 'Dr. Williams', 'specialty' => 'Neurology'],
            ['name' => 'Dr. Brown', 'specialty' => 'Pediatrics'],
            ['name' => 'Dr. Jones', 'specialty' => 'Orthopedics'],
        ];

        foreach ($professionals as $professional) {
            HealthcareProfessional::create($professional);
        }
    }
}
