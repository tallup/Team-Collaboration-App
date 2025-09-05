<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Task;

class TaskAssigned extends Notification implements ShouldQueue
{
    use Queueable;

    protected $task;
    protected $assignedBy;

    /**
     * Create a new notification instance.
     */
    public function __construct(Task $task, $assignedBy = null)
    {
        $this->task = $task;
        $this->assignedBy = $assignedBy;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $assignedByName = $this->assignedBy ? $this->assignedBy->name : 'System';
        
        return (new MailMessage)
            ->subject('New Task Assigned: ' . $this->task->title)
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('You have been assigned a new task.')
            ->line('**Task Details:**')
            ->line('• **Title:** ' . $this->task->title)
            ->line('• **Description:** ' . ($this->task->description ?: 'No description provided'))
            ->line('• **Priority:** ' . ucfirst($this->task->priority))
            ->line('• **Status:** ' . ucfirst($this->task->status))
            ->line('• **Due Date:** ' . ($this->task->due_date ? $this->task->due_date->format('M d, Y') : 'No due date set'))
            ->line('• **Assigned by:** ' . $assignedByName)
            ->when($this->task->project, function ($message) {
                return $message->line('• **Project:** ' . $this->task->project->name);
            })
            ->action('View Task', url('/admin/custom-tasks'))
            ->line('Thank you for using USGamNeeds Team Collaboration!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'task_id' => $this->task->id,
            'task_title' => $this->task->title,
            'assigned_by' => $this->assignedBy ? $this->assignedBy->name : 'System',
        ];
    }
}