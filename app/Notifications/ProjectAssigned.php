<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Project;

class ProjectAssigned extends Notification implements ShouldQueue
{
    use Queueable;

    protected $project;
    protected $assignedBy;

    /**
     * Create a new notification instance.
     */
    public function __construct(Project $project, $assignedBy = null)
    {
        $this->project = $project;
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
            ->subject('New Project Assignment: ' . $this->project->name)
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('You have been assigned to a new project.')
            ->line('**Project Details:**')
            ->line('• **Name:** ' . $this->project->name)
            ->line('• **Description:** ' . ($this->project->description ?: 'No description provided'))
            ->line('• **Status:** ' . ucfirst($this->project->status))
            ->line('• **Start Date:** ' . ($this->project->start_date ? $this->project->start_date->format('M d, Y') : 'Not set'))
            ->line('• **Due Date:** ' . ($this->project->due_date ? $this->project->due_date->format('M d, Y') : 'Not set'))
            ->line('• **Assigned by:** ' . $assignedByName)
            ->action('View Project', url('/admin/custom-projects'))
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
            'project_id' => $this->project->id,
            'project_name' => $this->project->name,
            'assigned_by' => $this->assignedBy ? $this->assignedBy->name : 'System',
        ];
    }
}