<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\Task;
use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Seeder;

class SampleDataSeeder extends Seeder
{
    public function run(): void
    {
        // Get the admin user
        $adminUser = User::where('email', 'admin@usgamneeds.com')->first();

        if (!$adminUser) {
            return;
        }

        // Get or create a default team
        $team = Team::firstOrCreate(
            ['name' => 'Development Team'],
            [
                'description' => 'Main development team for all projects',
                'owner_id' => $adminUser->id,
                'is_public' => true,
            ]
        );

        // Create sample projects
        $projects = [
            [
                'name' => 'Website Redesign',
                'description' => 'Complete redesign of the company website with modern UI/UX',
                'status' => 'active',
                'start_date' => now()->subDays(30),
                'due_date' => now()->addDays(60),
                'owner_id' => $adminUser->id,
                'team_id' => $team->id,
            ],
            [
                'name' => 'Mobile App Development',
                'description' => 'Develop a new mobile application for iOS and Android',
                'status' => 'planning',
                'start_date' => now()->addDays(7),
                'due_date' => now()->addDays(120),
                'owner_id' => $adminUser->id,
                'team_id' => $team->id,
            ],
            [
                'name' => 'Database Migration',
                'description' => 'Migrate legacy database to new cloud infrastructure',
                'status' => 'completed',
                'start_date' => now()->subDays(60),
                'due_date' => now()->subDays(10),
                'owner_id' => $adminUser->id,
                'team_id' => $team->id,
            ],
        ];

        foreach ($projects as $projectData) {
            $project = Project::firstOrCreate(
                ['name' => $projectData['name']],
                $projectData
            );

            // Only create tasks if the project was just created
            if ($project->wasRecentlyCreated) {
                // Create sample tasks for each project
                $tasks = [
                    [
                        'title' => 'Design Mockups',
                        'description' => 'Create wireframes and design mockups',
                        'status' => 'completed',
                        'priority' => 'high',
                        'due_date' => now()->addDays(7),
                        'assigned_to' => $adminUser->id,
                        'created_by' => $adminUser->id,
                    ],
                    [
                        'title' => 'Frontend Development',
                        'description' => 'Implement the frontend components',
                        'status' => 'in_progress',
                        'priority' => 'medium',
                        'due_date' => now()->addDays(14),
                        'assigned_to' => $adminUser->id,
                        'created_by' => $adminUser->id,
                    ],
                    [
                        'title' => 'Backend API',
                        'description' => 'Develop RESTful API endpoints',
                        'status' => 'todo',
                        'priority' => 'high',
                        'due_date' => now()->addDays(21),
                        'assigned_to' => $adminUser->id,
                        'created_by' => $adminUser->id,
                    ],
                ];

                foreach ($tasks as $taskData) {
                    $taskData['project_id'] = $project->id;
                    Task::create($taskData);
                }
            }
        }

        $this->command->info('Sample data created successfully!');
    }
}
