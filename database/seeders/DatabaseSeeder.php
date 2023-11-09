<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Team;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $admin = $this->createAdmin();
        $this->createUsers($admin);
    }

    private function createAdmin(): User
    {
        $admin = User::factory()->create([
            'name' => 'Admin',
            'email' => env('ADMIN_EMAIL'),
            'password' => bcrypt(env('ADMIN_PASSWORD')),
        ]);

        $adminTeam = Team::create([
            'user_id' => $admin->id,
            'name' => 'Admin Team',
            'personal_team' => true,
        ]);

        $admin->teams()->attach($adminTeam->id, ['role' => 'admin']);

        return $admin;
    }

    private function createUsers(User $admin): void
    {
        $publicTeam = Team::create([
            'user_id' => $admin->id,
            'name' => 'Public Team',
            'personal_team' => false,
        ]);

        User::factory(10)->create()->each(function ($member) use ($publicTeam) {
            $member->teams()->attach($publicTeam->id, ['role' => 'member']);
            $member->forceFill([
                'current_team_id' => $publicTeam->id,
            ])->save();
        });
    }
}
