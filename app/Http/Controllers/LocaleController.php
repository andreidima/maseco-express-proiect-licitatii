<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Validation\Rule;

class LocaleController extends Controller
{
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'locale' => ['required', 'string', Rule::in(['ro', 'en'])],
        ]);

        $locale = $validated['locale'];

        if (Auth::check()) {
            $request->session()->put('locale', $locale);
            Cookie::queue(Cookie::forget('locale'));
        } else {
            $request->session()->forget('locale');
            Cookie::queue(cookie('locale', $locale, 60 * 24 * 365));
        }

        return redirect()->back();
    }
}
