<?php

namespace Modules\MLBBToolManagement\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

/**
 * Authentication Controller for MLBB Theme
 * 
 * Handles login and registration when MLBB Tournament theme is active
 */
class AuthController extends Controller
{
    /**
     * Show login form
     */
    public function showLogin()
    {
        $activeTheme = config('cms.themes.active_theme', 'BasicTheme');
        
        if ($activeTheme === 'mlbb-tool-management-theme') {
            return view('mlbb-tool-management-theme::pages.login');
        }
        
        // Fallback to Filament login
        return redirect()->route('filament.admin.auth.login');
    }
    
    /**
     * Handle login request
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $remember = $request->filled('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            return redirect()->intended(route('mlbb.dashboard'));
        }

        throw ValidationException::withMessages([
            'email' => __('The provided credentials do not match our records.'),
        ]);
    }
    
    /**
     * Show registration form
     */
    public function showRegister()
    {
        $activeTheme = config('cms.themes.active_theme', 'BasicTheme');
        
        if ($activeTheme === 'mlbb-tool-management-theme') {
            return view('mlbb-tool-management-theme::pages.register');
        }
        
        // Fallback to Filament
        return redirect()->route('filament.admin.auth.login');
    }
    
    /**
     * Handle registration request
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:users'],
            'first_name' => ['nullable', 'string', 'max:255'],
            'last_name' => ['nullable', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'terms' => ['accepted'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        // Optionally assign a default role
        // $user->assignRole('user');

        Auth::login($user);

        return redirect()->route('mlbb.dashboard');
    }
    
    /**
     * Handle logout request
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        $activeTheme = config('cms.themes.active_theme', 'BasicTheme');
        
        if ($activeTheme === 'mlbb-tool-management-theme') {
            return redirect()->route('mlbb.auth.login');
        }

        return redirect()->route('filament.admin.auth.login');
    }
}
