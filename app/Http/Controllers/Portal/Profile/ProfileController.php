<?php

namespace App\Http\Controllers\Portal\Profile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();
        return view('portal.profile.index', compact('user'));
    }

    public function update(Request $request): RedirectResponse
    {
        $user = auth()->user();

        $validated = $request->validate([
            'first_name' => ['required', 'string', 'min:2', 'max:50', 'regex:/^[a-zA-ZğüşıöçĞÜŞİÖÇ\s]+$/u'],
            'last_name' => ['required', 'string', 'min:2', 'max:50', 'regex:/^[a-zA-ZğüşıöçĞÜŞİÖÇ\s]+$/u'],
            'email' => ['required', 'email:rfc,dns', 'max:255', 'unique:users,email,' . $user->id],
            'phone' => ['nullable', 'string', 'max:20', 'regex:/^[\+]?[0-9\s\-\(\)]+$/'],
            'current_password' => ['nullable', 'required_with:password', 'current_password'],
            'password' => ['nullable', 'confirmed', Password::min(8)->mixedCase()->numbers()->symbols()],
        ]);

        // Şifre değişikliği varsa
        if ($request->filled('password')) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        // current_password'ı kaldır
        unset($validated['current_password']);

        $user->update($validated);

        return redirect()
            ->route('portal.profile.index')
            ->with('success', __('profile.messages.updated_successfully'));
    }
} 