<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
    public function index()
    {
        $title = 'Login';

        return view('auth.login', compact('title'));
    }

    public function store(LoginRequest $request): RedirectResponse
    {
        try {
            $credentials = $request->only('email', 'password');

            if (auth()->attempt($credentials)) {

                // alihkan ke halaman dashboard
                return redirect()->intended(route('dashboard'))
                    ->with('success', 'Selamat datang ' . auth()->user()->name);
            }
        }catch (Exception $exception){
            Log::error($exception->getMessage());
            return redirect()->back()->with('error', 'Gagal masuk');
        }

        return to_route('login')->with('error', 'Cek kembali akun Anda');
    }

    public function logout(Request $request): RedirectResponse
    {
        try {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return to_route('login')->with('success', 'Berhasil logout.');
        }catch (Exception $exception) {
            Log::error($exception->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat logout.');
        }
    }
}
