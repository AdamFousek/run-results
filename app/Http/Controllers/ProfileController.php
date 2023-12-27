<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Http\Transformers\Runner\RunnerTransformer;
use App\Models\PairRunnerLog;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class ProfileController extends Controller
{
    public function __construct(
        private readonly RunnerTransformer $runnerTransformer,
    ) {
    }

    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): Response
    {
        $user = $request->user();
        if (!$user instanceof User) {
            abort(403);
        }

        $verified = (bool)$request->get('verified');

        if ($verified) {
            $this->withMessage(self::ALERT_SUCCESS, trans('messages.profile_mail_verified'));
        }

        $pairRunnerLimit = PairRunnerLog::LIMIT - PairRunnerLog::where('user_id', $user->id)->count();

        return Inertia::render('Profile/Edit', [
            'mustVerifyEmail' => true, // $user instanceof MustVerifyEmail,
            'status' => session('status'),
            'pairRunnerLimit' => $pairRunnerLimit,
            'runner' => $user->runner ? $this->runnerTransformer->transform($user->runner) : null,
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();

        if ($user === null) {
            abort(403);
        }

        $user->fill($request->validated());

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return Redirect::route('profile.edit');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        if ($user === null) {
            abort(403);
        }

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
