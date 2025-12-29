<?php

namespace App\Http\Controllers\Participant;

use App\Http\Controllers\Controller;
use App\Http\Requests\Participant\ParticipantProfileUpdateRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ParticipantProfileController extends Controller
{
    public function edit()
    {
        return view('participant.profile.edit', [
            'user' => Auth::user(),
        ]);
    }

    public function update(ParticipantProfileUpdateRequest $request)
    {
        $user = Auth::user();

        $data = $request->validated();

        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $user->update($data);

        return redirect()
            ->route('participant.profil.edit')
            ->with('status', __('flash.profile_updated'));
    }
}
