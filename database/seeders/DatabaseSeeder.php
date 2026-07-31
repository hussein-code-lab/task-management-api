<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()
            ->count(5)
            ->create()
            ->each(function ($user) {

                $user->projects()
                    ->saveMany(
                        Project::factory()
                            ->count(3)
                            ->make()
                    );

                $user->projects()
                    ->each(function ($project) {

                        $project->tasks()
                            ->saveMany(
                                Task::factory()
                                    ->count(5)
                                    ->make()
                            );

                    });

            });
    }
}
