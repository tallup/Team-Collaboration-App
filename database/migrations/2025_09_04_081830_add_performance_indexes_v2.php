<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add indexes for tasks table
        Schema::table('tasks', function (Blueprint $table) {
            if (!Schema::hasIndex('tasks', ['status', 'created_at'])) {
                $table->index(['status', 'created_at']);
            }
            if (!Schema::hasIndex('tasks', ['priority', 'status'])) {
                $table->index(['priority', 'status']);
            }
            if (!Schema::hasIndex('tasks', ['due_date', 'status'])) {
                $table->index(['due_date', 'status']);
            }
            if (!Schema::hasIndex('tasks', ['project_id', 'status'])) {
                $table->index(['project_id', 'status']);
            }
            if (!Schema::hasIndex('tasks', ['created_at'])) {
                $table->index('created_at');
            }
        });

        // Add indexes for projects table
        Schema::table('projects', function (Blueprint $table) {
            if (!Schema::hasIndex('projects', ['status', 'created_at'])) {
                $table->index(['status', 'created_at']);
            }
            if (!Schema::hasIndex('projects', ['user_id', 'status'])) {
                $table->index(['user_id', 'status']);
            }
            if (!Schema::hasIndex('projects', ['created_at'])) {
                $table->index('created_at');
            }
        });

        // Add indexes for users table
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasIndex('users', ['created_at'])) {
                $table->index('created_at');
            }
            if (!Schema::hasIndex('users', ['email'])) {
                $table->index('email');
            }
        });

        // Add indexes for comments table
        Schema::table('comments', function (Blueprint $table) {
            if (!Schema::hasIndex('comments', ['commentable_type', 'commentable_id'])) {
                $table->index(['commentable_type', 'commentable_id']);
            }
            if (!Schema::hasIndex('comments', ['created_at'])) {
                $table->index('created_at');
            }
        });

        // Add indexes for pivot tables
        Schema::table('task_user', function (Blueprint $table) {
            if (!Schema::hasIndex('task_user', ['task_id', 'user_id'])) {
                $table->index(['task_id', 'user_id']);
            }
            if (!Schema::hasIndex('task_user', ['user_id'])) {
                $table->index('user_id');
            }
        });

        Schema::table('project_user', function (Blueprint $table) {
            if (!Schema::hasIndex('project_user', ['project_id', 'user_id'])) {
                $table->index(['project_id', 'user_id']);
            }
            if (!Schema::hasIndex('project_user', ['user_id'])) {
                $table->index('user_id');
            }
        });

        Schema::table('team_user', function (Blueprint $table) {
            if (!Schema::hasIndex('team_user', ['team_id', 'user_id'])) {
                $table->index(['team_id', 'user_id']);
            }
            if (!Schema::hasIndex('team_user', ['user_id'])) {
                $table->index('user_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove indexes for tasks table
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropIndex(['status', 'created_at']);
            $table->dropIndex(['priority', 'status']);
            $table->dropIndex(['due_date', 'status']);
            $table->dropIndex(['project_id', 'status']);
            $table->dropIndex(['created_at']);
        });

        // Remove indexes for projects table
        Schema::table('projects', function (Blueprint $table) {
            $table->dropIndex(['status', 'created_at']);
            $table->dropIndex(['user_id', 'status']);
            $table->dropIndex(['created_at']);
        });

        // Remove indexes for users table
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['created_at']);
            $table->dropIndex(['email']);
        });

        // Remove indexes for comments table
        Schema::table('comments', function (Blueprint $table) {
            $table->dropIndex(['commentable_type', 'commentable_id']);
            $table->dropIndex(['created_at']);
        });

        // Remove indexes for pivot tables
        Schema::table('task_user', function (Blueprint $table) {
            $table->dropIndex(['task_id', 'user_id']);
            $table->dropIndex(['user_id']);
        });

        Schema::table('project_user', function (Blueprint $table) {
            $table->dropIndex(['project_id', 'user_id']);
            $table->dropIndex(['user_id']);
        });

        Schema::table('team_user', function (Blueprint $table) {
            $table->dropIndex(['team_id', 'user_id']);
            $table->dropIndex(['user_id']);
        });
    }
};