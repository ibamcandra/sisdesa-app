<?php

namespace App\Mail;

use App\Models\JobApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class InterviewNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $application;
    public $interviewData;

    public function __construct(JobApplication $application, array $interviewData)
    {
        $this->application = $application;
        $this->interviewData = $interviewData;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Undangan Interview - ' . $this->application->vacancy->title,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.interview',
        );
    }
}
