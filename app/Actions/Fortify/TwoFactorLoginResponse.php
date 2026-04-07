<?php

declare(strict_types=1);

namespace App\Actions\Fortify;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Laravel\Fortify\Contracts\TwoFactorLoginResponse as TwoFactorLoginResponseContract;
use Laravel\Fortify\Fortify;
use Symfony\Component\HttpFoundation\Response;

class TwoFactorLoginResponse implements TwoFactorLoginResponseContract
{
    public function toResponse($request): Response
    {
        if ($request->wantsJson()) {
            return new JsonResponse('', 204);
        }

        return redirect()->intended($this->redirectTo($request));
    }

    private function redirectTo(Request $request): string
    {
        $user = $request->user();

        if ($user !== null && $this->shouldRedirectToAdmin($user)) {
            return route('lms.admin.courses.index');
        }

        return Fortify::redirects('login');
    }

    private function shouldRedirectToAdmin(Authenticatable $user): bool
    {
        return $user->can('dashboard.view')
            || $user->can('courses.create')
            || $user->can('courses.update')
            || $user->can('lessons.create')
            || $user->can('users.view');
    }
}
