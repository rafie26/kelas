<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\admin;
use App\Models\siswa;
use App\Models\guru;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Services\AuthService;
use App\Services\RegistrationService;

class adminController extends Controller
{
    protected $authService;
    protected $registrationService;

    public function __construct(AuthService $authService, RegistrationService $registrationService)
    {
        $this->authService = $authService;
        $this->registrationService = $registrationService;
    }
    public function landing()
    {
        return view('landing');
    }

    public function formLogin()
    {
        return view('login');
    }

    public function prosesLogin(LoginRequest $request)
    {
        if ($this->authService->login($request->validated())) {
            return redirect()->route('home');
        }
        return back()->with('error', 'Username atau password salah.');
    }

    public function logout()
    {
        $this->authService->logout();
        return redirect()->route('landing');
    }

    public function formRegister()
    {
        return view('register');
    }

    public function prosesRegister(RegisterRequest $request)
    {
        try {
            $this->registrationService->register($request->validated());
            return redirect()->route('formLogin')->with('success', 'Registrasi berhasil! Silakan login.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Registrasi gagal: ' . $e->getMessage());
        }
    }
    public function home()
    {
        $data = $this->authService->getHomeData();
        return view('home', $data);
    }



}
