<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CreateAdminUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:create {email} {password} {name?} {--force}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create an admin user for Filament';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        $password = $this->argument('password');
        $name = $this->argument('name') ?? 'Admin User';
        $force = $this->option('force');

        // Check if user already exists
        $existingUser = User::where('email', $email)->first();
        
        if ($existingUser && !$force) {
            $this->error("User with email {$email} already exists!");
            $this->info("Use --force to update existing user to admin.");
            return 1;
        }

        try {
            if ($existingUser && $force) {
                // Update existing user to admin
                $existingUser->update([
                    'name' => $name,
                    'password' => Hash::make($password),
                    'is_admin' => true,
                    'email_verified_at' => now(),
                ]);
                $this->info("Existing user updated to admin successfully!");
            } else {
                // Create new admin user
                User::create([
                    'name' => $name,
                    'email' => $email,
                    'password' => Hash::make($password),
                    'is_admin' => true,
                    'email_verified_at' => now(),
                ]);
                $this->info("Admin user created successfully!");
            }

            $this->info("Email: {$email}");
            $this->info("Name: {$name}");
            $this->info("Admin: Yes");
            
            return 0;
        } catch (\Exception $e) {
            $this->error("Failed to create/update admin user: " . $e->getMessage());
            return 1;
        }
    }
}
