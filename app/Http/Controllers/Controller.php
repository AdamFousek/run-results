<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    protected const ALERT_SUCCESS = 'success';

    protected const ALERT_WARNING = 'warning';

    protected const ALERT_ERROR = 'error';

    protected const ALERT_INFO = 'info';

    protected function withMessage(string $type, string $message): void
    {
        request()->session()->flash('alert', [
            'type' => $type,
            'header' => trans('messages.' . ucfirst($type)),
            'message' => trans($message),
        ]);
    }

    protected function getUser(): User
    {
        $user = Auth::user();
        if (! $user instanceof User) {
            abort(403);
        }

        return $user;
    }
    /**
     * @param string $name
     * @param array<string, mixed> $parameter
     * @return RedirectResponse
     */
    protected function toRoute(string $name, array $parameter = []): RedirectResponse
    {
        return to_route($name, $parameter, 303);
    }
}
