<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Notifications\ProfileUpdated;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Show the form for editing the authenticated user's profile.
     */
    public function edit(): View
    {
        return view('profile.edit', [
            'user' => Auth::user(),
        ]);
    }

    /**
     * Update the authenticated user's profile and notify them of the changes.
     */
    public function update(Request $request): RedirectResponse
    {
        $user = Auth::user();

        $validated = $request->validate([
            'username' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => ['nullable', Password::defaults()],
        ]);

        $user->update([
            ...Arr::except($validated, ['password']),
            ...$request->filled('password') ? ['password' => $validated['password']] : [],
        ]);

        $changes = array_keys($user->getChanges());

        if ($changes !== []) {
            $user->notify(new ProfileUpdated($changes));
        }

        return redirect()->route('profile.edit')->with('success', 'Your profile has been updated.');
    }
}
