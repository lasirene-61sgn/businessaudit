<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class CustomerAuthController extends Controller
{
    public function showRegisterForm()
    {
        return view('customer.auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name'          => 'required|string|max:255',
            'email'         => 'required|email|unique:customers,email',
            'mobile_number' => 'required|string|unique:customers,mobile_number',
            'password'      => 'required|string|min:6|confirmed', // Requires password_confirmation field
            'profile_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = $request->only(['name', 'email', 'mobile_number', 'password', 'profile_image']);

        if ($request->hasFile('profile_image')) {
            $data['profile_image'] = $request->file('profile_image')->store('profile', 'public');
        }

        $customer = Customer::create($data);

        $otp = rand(100000, 999999);
        $customer->update(['otp' => $otp]);

        $this->sendMsg91Otp($customer, $otp);

        session(['pending_customer_id' => $customer->id]);
        return redirect()->route('customer.verify.page')->with('success', 'Registration Done');
    }

    public function showLoginForm()
    {
        return view('customer.auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'mobile_number' => 'required|string',
            'password' => 'required|string',
        ]);

        $customer = Customer::where('mobile_number', $request->mobile_number)->first();

        if (!$customer || !Hash::check($request->password, $customer->password)) {
            throw ValidationException::withMessages([
                'mobile_number' => __('auth.failed'),
            ]);
        }

        if (is_null($customer->mobile_verified_at)) {
            $otp = rand(100000, 999999);
            $customer->update(['otp' => $otp]);
            
            $this->sendMsg91Otp($customer, $otp);
            
            session(['pending_customer_id' => $customer->id]);
            return redirect()->route('customer.verify.page')->with('info', 'Unverfied Genertae otp');
        }

        Auth::guard('customer')->login($customer, $request->filled('remember'));
        $request->session()->regenerate();
        return redirect()->intended(route('customer.dashboard'));
    }
    public function showVerifyPage()
    {
        if (!session()->has('pending_customer_id')) {
            return redirect()->route('customer.login');
        }
        return view('customer.auth.verify');
    }

    /**
     * Handle OTP verification code submission.
     */
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|numeric',
        ]);

        if (!session()->has('pending_customer_id')) {
            return redirect()->route('customer.login');
        }

        $customer = Customer::findOrFail(session('pending_customer_id'));

        // DUMMY OTP CONDITION: Accept the real database OTP OR the dummy code '123456'
        if ($request->otp == $customer->otp || $request->otp == '123456') {

            $customer->update([
                'otp' => null,
                'mobile_verified_at' => now(), // Mark as verified
            ]);

            // Log the customer in securely
            Auth::guard('customer')->login($customer);

            $request->session()->forget('pending_customer_id');
            $request->session()->regenerate();

            return redirect()->route('customer.dashboard');
        }

        throw ValidationException::withMessages([
            'otp' => 'The verification code you entered is invalid.',
        ]);
    }

    /**
     * Log the customer out securely.
     */
    public function logout(Request $request)
    {
        Auth::guard('customer')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('customer.login');
    }

    /**
     * Send OTP via MSG91 WhatsApp API
     */
    private function sendMsg91Otp($customer, $otp)
    {
        $authKey = env('MSG91_AUTH_KEY');
        
        // Strip out any non-numeric characters and ensure country code (assuming 91 for India if not provided)
        $mobileNumber = preg_replace('/[^0-9]/', '', $customer->mobile_number);
        if (strlen($mobileNumber) == 10) {
            $mobileNumber = '91' . $mobileNumber;
        }

        if ($authKey) {
            \Illuminate\Support\Facades\Http::withHeaders([
                'authkey' => $authKey,
                'Content-Type' => 'application/json',
            ])->post('https://api.msg91.com/api/v5/whatsapp/whatsapp-outbound-message/bulk/', [
                'integrated_number' => env('MSG91_INTEGRATED_NUMBER', '919360777089'),
                'content_type' => 'template',
                'payload' => [
                    'messaging_product' => 'whatsapp',
                    'type' => 'template',
                    'template' => [
                        'name' => 'audit',
                        'language' => [
                            'code' => 'en',
                            'policy' => 'deterministic'
                        ],
                        'namespace' => 'bc3735fb_a2e9_4e83_8b62_377bca25c09f',
                        'to_and_components' => [
                            [
                                'to' => [
                                    $mobileNumber
                                ],
                                'components' => [
                                    'body_1' => [
                                        'type' => 'text',
                                        'value' => (string)$otp
                                    ],
                                    'button_1' => [
                                        'subtype' => 'url',
                                        'type' => 'text',
                                        'value' => (string)$otp
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ]);
        }
    }
}
