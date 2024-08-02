<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Inertia\Inertia;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            $sentryEnabled = (bool)config('SENTRY_ENABLE');
            if ($sentryEnabled && app()->bound('sentry')) {
                app('sentry')->captureException($e);
            }
        });
    }

    public function render($request, Throwable $e)
    {
        $response = parent::render($request, $e);
        $status = $response->getStatusCode();

        if (!app()->environment(['local', 'testing'])) {

            $returnLink = url()->previous() === $request->url() ? route('welcome') : url()->previous();

            $session = null;
            try {
                $session = $request->session();
            } catch (Throwable $e) {
                // do nothing
            }

            $data = [
                'locale' => app()->getLocale(),
                'auth' => [
                    'user' => $request->user()?->only('id', 'username', 'email', 'role', 'email_verified_at'),
                    'isAdmin' => $request->user()?->isAdmin(),
                    'token' => csrf_token(),
                ],
                'flash' => [
                    'alert' => $session?->get('alert'),
                ],
                'returnLink' => $returnLink,
            ];

            return match ($status) {
                404 => Inertia::render('Errors/404', $data)->toResponse($request)->setStatusCode($status),
                500, 503 => Inertia::render('Errors/500', $data)->toResponse($request)->setStatusCode($status),
                403 => Inertia::render('Errors/403', $data)->toResponse($request)->setStatusCode($status),
                401 => Inertia::render('Errors/401', $data)->toResponse($request)->setStatusCode($status),
                419 => redirect()->back()->withErrors(['status' => __('The page expired, please try again.')]),
                default => $response
            };

        }

        return $response;

    }

}
