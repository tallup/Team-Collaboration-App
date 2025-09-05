<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ClearAppCache extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:clear-cache';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear all application caches for better performance';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Clearing all caches...');
        
        // Clear application cache
        $this->call('cache:clear');
        $this->info('✓ Application cache cleared');
        
        // Clear configuration cache
        $this->call('config:clear');
        $this->info('✓ Configuration cache cleared');
        
        // Clear route cache
        $this->call('route:clear');
        $this->info('✓ Route cache cleared');
        
        // Clear view cache
        $this->call('view:clear');
        $this->info('✓ View cache cleared');
        
        // Clear compiled services
        $this->call('clear-compiled');
        $this->info('✓ Compiled services cleared');
        
        // Optimize for production
        if (app()->environment('production')) {
            $this->call('config:cache');
            $this->call('route:cache');
            $this->call('view:cache');
            $this->info('✓ Production optimizations applied');
        }
        
        $this->info('All caches cleared successfully!');
    }
}