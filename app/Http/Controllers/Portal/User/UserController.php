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
        $query = User::query();
        
        // Search
        if ($search = $request->search) {
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%");
            });
        }
        
        // Type filter
        if ($type = $request->type) {
            $query->byType($type);
        }
        
        // Status filter
        if ($status = $request->status) {
            $query->where('status', $status);
        }
        
        // Sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        
        // Handle name sorting (combine first_name and last_name)
        if ($sortBy === 'name') {
            $query->orderByRaw("CONCAT(first_name, ' ', last_name) {$sortOrder}");
        } else {
            // Validate sortable columns
            $sortableColumns = ['type', 'status', 'last_login_at', 'created_at'];
            if (in_array($sortBy, $sortableColumns)) {
                $query->orderBy($sortBy, $sortOrder);
            } else {
                $query->orderBy('created_at', 'desc');
            }
        }
        
        $users = $query->paginate(5)->withQueryString();

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
            ->with('success', __('user.messages.created_successfully'));
    }

    public function show(User $user): View
    {
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
            ->with('success', __('user.messages.updated_successfully'));
    }

    public function destroy(User $user): RedirectResponse
    {
        $user->delete();

        return redirect()->route('portal.user.index')
            ->with('success', __('user.messages.deleted_successfully'));
    }
} 