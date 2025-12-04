<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Frontend\ContactRequest;
use App\Models\Contact;
use App\Services\RateLimitService;
use App\Services\MailTemplateService;
use App\Services\MailConfigService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use RealRashid\SweetAlert\Facades\Alert;

class ContactController extends Controller
{
    private RateLimitService $rateLimitService;
    private MailTemplateService $mailTemplateService;
    private MailConfigService $mailConfigService;

    public function __construct()
    {
        // Use factory method for contact-specific rate limiting
        $this->rateLimitService = RateLimitService::forContact(
            maxAttempts: config('auth.contact_rate_limit.max_attempts', 3),
            decayMinutes: config('auth.contact_rate_limit.decay_minutes', 5)
        );
        $this->mailTemplateService = app(MailTemplateService::class);
        $this->mailConfigService = app(MailConfigService::class);
    }

    public function contact()
    {
        return view('frontend.modules.contact.index');
    }

    public function postContact(ContactRequest $request)
    {
        $ip = $request->ip();

        // Check rate limiting
        if ($this->rateLimitService->isBlocked($ip)) {
            Alert::error('Quá số lượng yêu cầu', $this->rateLimitService->getErrorMessage());

            return back();
        }

        // Create contact record
        $contact = $this->createContact($request);

        // Send notification email to admin
        $this->sendContactNotification($contact);

        // Increment attempts after successful creation
        $this->rateLimitService->incrementAttempts($ip);

        Alert::success('Đã gửi yêu cầu thành công', 'Chúng tôi sẽ liên hệ sớm nhất có thể');

        return redirect()->route('web.contact');
    }

    /**
     * Create contact record from request
     */
    private function createContact(ContactRequest $request): Contact
    {
        $data = $request->except('_token');
        $data['uuid'] = Str::uuid();
        $data['created_at'] = new \DateTime;
        $data['status'] = 0;

        return Contact::create($data);
    }

    /**
     * Send contact notification email to admin
     */
    private function sendContactNotification(Contact $contact): void
    {
        try {
            $email = DB::table('tp_systems')->value('email_alert');
            Log::info('Email alert from system: ' . $email);

            if ($email) {
                $template = $this->mailTemplateService->getActiveByType('contact');
                Log::info('Contact template: ', ['template' => $template]);

                if ($template) {
                    $mailer = $this->mailConfigService->createMailer();
                    $mailer->to($email)->send(new \App\Mail\AlertContact($contact, $template));
                    Log::info('Contact notification email sent to: ' . $email);
                } else {
                    Log::warning('No active contact template found');
                }
            } else {
                Log::warning('No email_alert set in system');
            }
        } catch (\Exception $e) {
            // Log error but don't fail the contact submission
            Log::error('Failed to send contact notification email: ' . $e->getMessage());
        }
    }
}
