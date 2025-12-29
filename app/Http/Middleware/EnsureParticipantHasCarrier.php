<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureParticipantHasCarrier
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if ($user && $user->role === 'Participant licitatii' && empty($user->carrier_id)) {
            return redirect()
                ->route('acasa')
                ->with('error', __('flash.account_not_linked_carrier'));
        }

        return $next($request);
    }
}
