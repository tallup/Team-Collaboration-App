<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Task;

class TaskStatusChanged extends Notification implements ShouldQueue
{
    use Queueable;

    protected $task;
    protected $oldStatus;
    protected $changedBy;

    /**
     * Create a new notification instance.
     */
    public function __construct(Task $task, $oldStatus, $changedBy = null)
    {
        $this->task = $task;
        $this->oldStatus = $oldStatus;
        $this->changedBy = $changedBy;
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
        $changedByName = $this->changedBy ? $this->changedBy->name : 'System';
        
        return (new MailMessage)
            ->subject('Task Status Updated: ' . $this->task->title)
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('A task status has been updated.')
            ->line('**Task Details:**')
            ->line('• **Title:** ' . $this->task->title)
            ->line('• **Status Changed:** ' . ucfirst($this->oldStatus) . ' → ' . ucfirst($this->task->status))
            ->line('• **Priority:** ' . ucfirst($this->task->priority))
            ->line('• **Due Date:** ' . ($this->task->due_date ? $this->task->due_date->format('M d, Y') : 'No due date set'))
            ->line('• **Changed by:** ' . $changedByName)
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
            'old_status' => $this->oldStatus,
            'new_status' => $this->task->status,
            'changed_by' => $this->changedBy ? $this->changedBy->name : 'System',
        ];
    }
}