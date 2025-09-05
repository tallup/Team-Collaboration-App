<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Task;
use App\Models\Comment;

class TaskCommentAdded extends Notification implements ShouldQueue
{
    use Queueable;

    protected $task;
    protected $comment;
    protected $commentedBy;

    /**
     * Create a new notification instance.
     */
    public function __construct(Task $task, Comment $comment, $commentedBy = null)
    {
        $this->task = $task;
        $this->comment = $comment;
        $this->commentedBy = $commentedBy;
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
        $commentedByName = $this->commentedBy ? $this->commentedBy->name : 'System';
        
        return (new MailMessage)
            ->subject('New Comment on Task: ' . $this->task->title)
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('A new comment has been added to a task you are assigned to.')
            ->line('**Task Details:**')
            ->line('• **Title:** ' . $this->task->title)
            ->line('• **Status:** ' . ucfirst($this->task->status))
            ->line('• **Priority:** ' . ucfirst($this->task->priority))
            ->when($this->task->project, function ($message) {
                return $message->line('• **Project:** ' . $this->task->project->name);
            })
            ->line('**Comment:**')
            ->line('"' . $this->comment->content . '"')
            ->line('• **Commented by:** ' . $commentedByName)
            ->line('• **Date:** ' . $this->comment->created_at->format('M d, Y \a\t g:i A'))
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
            'comment_id' => $this->comment->id,
            'comment_content' => $this->comment->content,
            'commented_by' => $this->commentedBy ? $this->commentedBy->name : 'System',
        ];
    }
}