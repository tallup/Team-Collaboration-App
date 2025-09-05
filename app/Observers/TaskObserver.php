<?php

namespace App\Observers;

use App\Models\Task;
use Illuminate\Support\Facades\Cache;

class TaskObserver
{
    /**
     * Handle the Task "created" event.
     */
    public function created(Task $task): void
    {
        $this->clearCache();
    }

    /**
     * Handle the Task "updated" event.
     */
    public function updated(Task $task): void
    {
        $this->clearCache();
    }

    /**
     * Handle the Task "deleted" event.
     */
    public function deleted(Task $task): void
    {
        $this->clearCache();
    }

    /**
     * Clear related caches when task is modified
     */
    private function clearCache(): void
    {
        // Clear dashboard cache for all users
        Cache::flush();
        
        // Or more specific cache clearing:
        // $users = \App\Models\User::pluck('id');
        // foreach ($users as $userId) {
        //     Cache::forget("dashboard_stats_{$userId}");
        //     Cache::forget("recent_tasks_{$userId}");
        //     Cache::forget("tasks_list_{$userId}");
        // }
    }
}