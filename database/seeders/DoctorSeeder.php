<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DoctorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
        [
                'name' => 'Dr. Sarah Ahmed',
                'specialty' => 'Cardiology',
                'city' => 'Casablanca',
                'yearsofexperience' => 12,
                'consultation_price' => 400,
                'available_days' => 'Mon,Wed,Fri',
            ],
            [
                'name' => 'Dr. Youssef Benali',
                'specialty' => 'Dermatology',
                'city' => 'Rabat',
                'yearsofexperience' => 8,
                'consultation_price' => 300,
                'available_days' => 'Tue,Thu',
            ],
            [
                'name' => 'Dr. Leila Mourad',
                'specialty' => 'Pediatrics',
                'city' => 'Marrakech',
                'yearsofexperience' => 15,
                'consultation_price' => 350,
                'available_days' => 'Mon-Fri',
            ],
            [
                'name' => 'Dr. Anas El Idrissi',
                'specialty' => 'Neurology',
                'city' => 'Fes',
                'yearsofexperience' => 10,
                'consultation_price' => 500,
                'available_days' => 'Wed,Thu',
            ],
            [
                'name' => 'Dr. Nadia Choukrallah',
                'specialty' => 'Gynecology',
                'city' => 'Tangier',
                'yearsofexperience' => 7,
                'consultation_price' => 300,
                'available_days' => 'Tue,Thu,Sat',
            ],
            [
                'name' => 'Dr. Karim El Fassi',
                'specialty' => 'Orthopedics',
                'city' => 'Agadir',
                'yearsofexperience' => 20,
                'consultation_price' => 450,
                'available_days' => 'Mon,Wed,Fri',
            ],
            [
                'name' => 'Dr. Sofia Belkacem',
                'specialty' => 'Ophthalmology',
                'city' => 'Casablanca',
                'yearsofexperience' => 9,
                'consultation_price' => 350,
                'available_days' => 'Mon-Fri',
            ],
            [
                'name' => 'Dr. Rachid Bensalem',
                'specialty' => 'ENT',
                'city' => 'Rabat',
                'yearsofexperience' => 6,
                'consultation_price' => 250,
                'available_days' => 'Tue,Thu',
            ],
            [
                'name' => 'Dr. Laila Khattabi',
                'specialty' => 'Psychiatry',
                'city' => 'Marrakech',
                'yearsofexperience' => 11,
                'consultation_price' => 400,
                'available_days' => 'Mon,Wed,Fri',
            ],
            [
                'name' => 'Dr. Omar El Amrani',
                'specialty' => 'General Medicine',
                'city' => 'Fes',
                'yearsofexperience' => 5,
                'consultation_price' => 200,
                'available_days' => 'Mon-Sat',
            ],];

        DB::table('doctors')->insert($data);
    }
}
