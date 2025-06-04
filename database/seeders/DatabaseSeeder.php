<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        // Executar o RoleSeeder primeiro
        $this->call([
            RoleSeeder::class,
        ]);

        // Obter os papéis
        $teacherRole = Role::where('name', 'teacher')->first();
        $studentRole = Role::where('name', 'student')->first();

        // Criar um professor
        $teacher = User::create([
            'name' => 'Professor',
            'email' => 'professor@example.com',
            'password' => Hash::make('password'),
        ]);
        $teacher->roles()->attach($teacherRole);

        // Criar um aluno
        $student = User::create([
            'name' => 'Aluno',
            'email' => 'aluno@example.com',
            'password' => Hash::make('password'),
        ]);
        $student->roles()->attach($studentRole);
    }
}
