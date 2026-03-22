<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class MentionNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $data;
    public $user;

    public function __construct($data, $user)
    {
        $this->data = $data;
        $this->user = $user;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'You were mentioned in ' . ($this->data['content_type'] === 'post' ? 'a post' : 'a comment') . ' on LifeVault',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.mention',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
