<?php

namespace App\Http\Requests\Portal;

use App\Actions\GuardPortalLoginAction;
use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<int, string>|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
            'remember' => ['sometimes', 'boolean'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'email.required' => 'Enter your email and password.',
            'password.required' => 'Enter your email and password.',
            'email.email' => 'Enter a valid email address.',
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws ValidationException
     */
    public function authenticate(GuardPortalLoginAction $guardPortalLogin): void
    {
        $ipAddress = (string) $this->ip();
        $blockMessage = $guardPortalLogin->blockMessage($ipAddress);

        if (is_string($blockMessage)) {
            throw ValidationException::withMessages([
                'email' => $blockMessage,
            ]);
        }

        if (! Auth::attempt($this->only('email', 'password'), $this->boolean('remember'))) {
            $guardPortalLogin->recordFailure($ipAddress);

            throw ValidationException::withMessages([
                'email' => $guardPortalLogin->blockMessage($ipAddress) ?? 'Those details were not recognised.',
            ]);
        }

        $user = Auth::user();

        if ($user instanceof User && $user->isSuspended()) {
            Auth::logout();

            $guardPortalLogin->recordFailure($ipAddress);

            throw ValidationException::withMessages([
                'email' => 'This account is suspended.',
            ]);
        }

        $guardPortalLogin->recordSuccess($ipAddress);
    }
}
