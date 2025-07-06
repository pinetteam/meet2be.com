<?php

namespace App\Http\Controllers\Portal\Profile;

use App\Http\Controllers\Controller;
use App\Http\Requests\Portal\Profile\UpdateProfileRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();
        return view('portal.profile.index', compact('user'));
    }

    public function update(UpdateProfileRequest $request): RedirectResponse
    {
        $user = Auth::user();
        
        $user->update($request->validated());
        
        return redirect()->route('portal.profile.index')
            ->with('success', 'Profiliniz başarıyla güncellendi.');
    }
} 