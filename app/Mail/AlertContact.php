<?php

namespace App\Mail;

use App\Models\Contact;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AlertContact extends Mailable
{
    use Queueable, SerializesModels;

    public $contact;
    public $template;

    /**
     * Create a new message instance.
     */
    public function __construct(Contact $contact, $template)
    {
        $this->contact = $contact;
        $this->template = $template;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->template->subject ?? 'New Contact Submission',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $content = $this->template->content ?? 'New contact: {contact_name} - {contact_email} - {contact_message}';

        // Replace placeholders with both formats for compatibility
        $replacements = [
            '{contact_name}' => $this->contact->fullname,
            '{contact_email}' => $this->contact->email,
            '{contact_phone}' => $this->contact->phone ?? '',
            '{contact_subject}' => $this->contact->subject ?? '',
            '{contact_message}' => nl2br(e($this->contact->message)),
            '{contact_date}' => $this->contact->created_at->format('d/m/Y H:i'),
            // Legacy support
            '{{fullname}}' => $this->contact->fullname,
            '{{email}}' => $this->contact->email,
            '{{phone}}' => $this->contact->phone ?? '',
            '{{subject}}' => $this->contact->subject ?? '',
            '{{message}}' => nl2br(e($this->contact->message))
        ];

        $content = str_replace(array_keys($replacements), array_values($replacements), $content);

        return new Content(
            htmlString: $content,
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
