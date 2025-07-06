<?php

namespace App\Http\Controllers\Portal\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Portal\User\StoreUserRequest;
use App\Http\Requests\Portal\User\UpdateUserRequest;
use App\Models\User\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $users = User::with('tenant')
            ->when($request->search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('first_name', 'like', "%{$search}%")
                      ->orWhere('last_name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhere('username', 'like', "%{$search}%");
                });
            })
            ->when($request->type, function ($query, $type) {
                $query->byType($type);
            })
            ->when($request->status, function ($query, $status) {
                $query->where('status', $status);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(15)
            ->withQueryString();

        return view('portal.user.index', compact('users'));
    }

    public function create(): View
    {
        return view('portal.user.create');
    }

    public function store(StoreUserRequest $request): RedirectResponse
    {
        $user = User::create($request->validated());

        return redirect()->route('portal.user.index')
            ->with('success', __('User created successfully'));
    }

    public function show(User $user): View
    {
        $user->load('tenant');
        return view('portal.user.show', compact('user'));
    }

    public function edit(User $user): View
    {
        return view('portal.user.edit', compact('user'));
    }

    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        $user->update($request->validated());

        return redirect()->route('portal.user.show', $user)
            ->with('success', __('User updated successfully'));
    }

    public function destroy(User $user): RedirectResponse
    {
        $user->delete();

        return redirect()->route('portal.user.index')
            ->with('success', __('User deleted successfully'));
    }
} 