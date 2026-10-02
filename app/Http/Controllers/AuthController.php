<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | REGISTER
    |--------------------------------------------------------------------------
    */

    /**
     * Menampilkan halaman register.
     */
    public function showRegister(): View|RedirectResponse
    {
        if (Auth::check()) {
            return $this->redirectAuthenticatedUser();
        }

        return view('auth.register');
    }

    /**
     * Memproses pendaftaran pengguna baru.
     */
    public function processRegister(Request $request): RedirectResponse
    {
        $validated = $request->validate(
            [
                'username' => [
                    'required',
                    'string',
                    'min:3',
                    'max:50',
                    'unique:users,username',
                ],
                'email' => [
                    'required',
                    'string',
                    'email',
                    'max:255',
                    'unique:users,email',
                ],
                'password' => [
                    'required',
                    'string',
                    'min:8',
                    'confirmed',
                ],
                'agreeTerms' => [
                    'accepted',
                ],
            ],
            [
                'username.required' => 'Username wajib diisi.',
                'username.min' => 'Username minimal terdiri dari 3 karakter.',
                'username.max' => 'Username maksimal terdiri dari 50 karakter.',
                'username.unique' => 'Username sudah digunakan.',

                'email.required' => 'Email wajib diisi.',
                'email.email' => 'Format email tidak valid.',
                'email.unique' => 'Email sudah terdaftar.',

                'password.required' => 'Password wajib diisi.',
                'password.min' => 'Password minimal terdiri dari 8 karakter.',
                'password.confirmed' => 'Konfirmasi password tidak sesuai.',

                'agreeTerms.accepted' => 'Anda harus menyetujui syarat dan ketentuan.',
            ]
        );

        $username = trim($validated['username']);
        $email = Str::lower(trim($validated['email']));

        $user = User::create([
            /*
             * Kolom name diisi agar kompatibel dengan migration bawaan Laravel
             * yang biasanya menjadikan kolom name sebagai field wajib.
             */
            'name' => $username,
            'username' => $username,
            'email' => $email,
            'password' => Hash::make($validated['password']),
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('gender');
    }

    /*
    |--------------------------------------------------------------------------
    | LOGIN
    |--------------------------------------------------------------------------
    */

    /**
     * Menampilkan halaman login.
     */
    public function showLogin(): View|RedirectResponse
    {
        if (Auth::check()) {
            return $this->redirectAuthenticatedUser();
        }

        return view('auth.login');
    }

    /**
     * Memproses login pengguna.
     */
    public function processLogin(Request $request): RedirectResponse
    {
        $validated = $request->validate(
            [
                'username' => [
                    'required',
                    'string',
                    'max:50',
                ],
                'password' => [
                    'required',
                    'string',
                ],
                'remember' => [
                    'nullable',
                    'boolean',
                ],
            ],
            [
                'username.required' => 'Username wajib diisi.',
                'password.required' => 'Password wajib diisi.',
            ]
        );

        $username = trim($validated['username']);
        $throttleKey = $this->loginThrottleKey($request, $username);

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);

            throw ValidationException::withMessages([
                'username' => "Terlalu banyak percobaan login. Coba kembali dalam {$seconds} detik.",
            ]);
        }

        $credentials = [
            'username' => $username,
            'password' => $validated['password'],
        ];

        $remember = $request->boolean('remember');

        if (! Auth::attempt($credentials, $remember)) {
            RateLimiter::hit($throttleKey, 60);

            throw ValidationException::withMessages([
                'username' => 'Username atau password yang Anda masukkan salah.',
            ]);
        }

        RateLimiter::clear($throttleKey);

        // Mencegah session fixation.
        $request->session()->regenerate();

        $routeName = Auth::user()->gender
            ? 'programs'
            : 'gender';

        return redirect()->intended(route($routeName));
    }

    /*
    |--------------------------------------------------------------------------
    | LOGOUT
    |--------------------------------------------------------------------------
    */

    /**
     * Mengeluarkan pengguna dari aplikasi.
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->route('landing')
            ->with('success', 'Anda berhasil logout.');
    }

    /*
    |--------------------------------------------------------------------------
    | FORGOT PASSWORD
    |--------------------------------------------------------------------------
    */

    /**
     * Menampilkan halaman lupa password.
     */
    public function showForgotPassword(): View|RedirectResponse
    {
        if (Auth::check()) {
            return $this->redirectAuthenticatedUser();
        }

        return view('auth.forgot-password');
    }

    /**
     * Mengirim link reset password ke email pengguna.
     */
    public function sendResetLink(Request $request): RedirectResponse
    {
        $validated = $request->validate(
            [
                'email' => [
                    'required',
                    'email',
                    'max:255',
                ],
            ],
            [
                'email.required' => 'Email wajib diisi.',
                'email.email' => 'Format email tidak valid.',
            ]
        );

        $status = Password::sendResetLink([
            'email' => Str::lower(trim($validated['email'])),
        ]);

        if ($status === Password::RESET_LINK_SENT) {
            return back()->with(
                'success',
                'Link reset password telah dikirim. Silakan periksa email Anda.'
            );
        }

        return back()
            ->withErrors([
                'email' => $this->passwordResetErrorMessage($status),
            ])
            ->onlyInput('email');
    }

    /**
     * Menampilkan halaman pembuatan password baru.
     */
    public function showResetPassword(
        Request $request,
        string $token
    ): View {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => $request->query('email'),
        ]);
    }

    /**
     * Memproses perubahan password.
     */
    public function resetPassword(Request $request): RedirectResponse
    {
        $validated = $request->validate(
            [
                'token' => [
                    'required',
                ],
                'email' => [
                    'required',
                    'email',
                    'max:255',
                ],
                'password' => [
                    'required',
                    'string',
                    'min:8',
                    'confirmed',
                ],
            ],
            [
                'token.required' => 'Token reset password tidak tersedia.',
                'email.required' => 'Email wajib diisi.',
                'email.email' => 'Format email tidak valid.',
                'password.required' => 'Password baru wajib diisi.',
                'password.min' => 'Password baru minimal terdiri dari 8 karakter.',
                'password.confirmed' => 'Konfirmasi password tidak sesuai.',
            ]
        );

        $status = Password::reset(
            [
                'email' => Str::lower(trim($validated['email'])),
                'password' => $validated['password'],
                'password_confirmation' => $request->password_confirmation,
                'token' => $validated['token'],
            ],
            function (User $user, string $password): void {
                $user->forceFill([
                    'password' => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return redirect()
                ->route('login')
                ->with(
                    'success',
                    'Password berhasil diubah. Silakan login menggunakan password baru.'
                );
        }

        return back()
            ->withErrors([
                'email' => $this->passwordResetErrorMessage($status),
            ])
            ->withInput($request->only('email'));
    }

    /*
    |--------------------------------------------------------------------------
    | HELPER METHODS
    |--------------------------------------------------------------------------
    */

    /**
     * Mengarahkan pengguna yang sudah login.
     */
    private function redirectAuthenticatedUser(): RedirectResponse
    {
        $routeName = Auth::user()->gender
            ? 'programs'
            : 'gender';

        return redirect()->route($routeName);
    }

    /**
     * Membuat identitas pembatas percobaan login.
     */
    private function loginThrottleKey(
        Request $request,
        string $username
    ): string {
        return Str::transliterate(
            Str::lower($username).'|'.$request->ip()
        );
    }

    /**
     * Membuat pesan reset password yang lebih mudah dipahami.
     */
    private function passwordResetErrorMessage(string $status): string
    {
        return match ($status) {
            Password::INVALID_USER =>
                'Email tersebut tidak terdaftar.',

            Password::INVALID_TOKEN =>
                'Link reset password tidak valid atau sudah kedaluwarsa.',

            Password::RESET_THROTTLED =>
                'Permintaan reset terlalu sering. Silakan tunggu beberapa saat.',

            default =>
                'Link reset password gagal dikirim. Silakan coba kembali.',
        };
    }
}