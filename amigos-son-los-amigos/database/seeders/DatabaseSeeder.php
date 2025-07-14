<?php

namespace Database\Seeders;

use App\Models\Bill;
use App\Models\Client;
use App\Models\Diagnosis;
use App\Models\Employee;
use App\Models\Pet;
use App\Models\Role;
use App\Models\User;
use App\Models\Vaccine;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        $this->call(RoleSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(AdministratorSeeder::class);
        $this->call(EmployeeSeeder::class);
        $this->call(ClientSeeder::class);
        $this->call(PetSeeder::class);
        $this->call(AppointmentSeeder::class);
        $this->call(BillSeeder::class);
        $this->call(VaccineSeeder::class);
        $this->call(DiagnosisSeeder::class);


    }
}
