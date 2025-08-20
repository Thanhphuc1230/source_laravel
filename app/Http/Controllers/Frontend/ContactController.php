<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Frontend\ContactRequest;
use App\Models\Contact;

use App\Services\RateLimitService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Str;

class ContactController extends Controller
{
    private RateLimitService $rateLimitService;

    public function __construct()
    {
        // Use factory method for contact-specific rate limiting
        $this->rateLimitService = RateLimitService::forContact(
            maxAttempts: config('auth.contact_rate_limit.max_attempts', 3),
            decayMinutes: config('auth.contact_rate_limit.decay_minutes', 5)
        );
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
        $data['created_at'] = new \DateTime();
        $data['status'] = 0;

        return Contact::create($data);
    }
}
