<?php

namespace App\Http\Controllers\Participant;

use App\Http\Controllers\Controller;
use App\Models\SupportThread;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SupportController extends Controller
{
    public function index()
    {
        $threads = SupportThread::where('participant_user_id', Auth::id())
            ->with('latestMessage')
            ->orderByDesc('last_activity_at')
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('participant.support.index', compact('threads'));
    }

    public function create()
    {
        return view('participant.support.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'subject' => ['required', 'string', 'max:150'],
            'type' => ['required', 'in:chat,problem'],
            'body' => ['required', 'string'],
            'problem_category' => ['nullable', 'string', 'max:60'],
            'problem_severity' => ['nullable', 'string', 'max:60'],
            'problem_summary' => ['nullable', 'string'],
        ]);

        if ($data['type'] === 'problem' && empty($data['problem_summary'])) {
            return back()->withInput()->withErrors(['problem_summary' => __('support.required_summary')]);
        }

        $thread = SupportThread::create([
            'participant_user_id' => Auth::id(),
            'subject' => $data['subject'],
            'type' => $data['type'],
            'status' => 'open',
            'problem_category' => $data['problem_category'],
            'problem_severity' => $data['problem_severity'],
            'problem_summary' => $data['problem_summary'],
            'last_activity_at' => now(),
        ]);

        $thread->messages()->create([
            'sender_id' => Auth::id(),
            'sender_role' => 'participant',
            'body' => $data['body'],
        ]);

        return redirect()->route('participant.support.show', $thread);
    }

    public function show(SupportThread $supportThread)
    {
        $this->authorizeParticipant($supportThread);

        $messages = $supportThread->messages()
            ->with('sender')
            ->orderBy('created_at')
            ->get();

        return view('participant.support.show', compact('supportThread', 'messages'));
    }

    public function storeMessage(Request $request, SupportThread $supportThread)
    {
        $this->authorizeParticipant($supportThread);

        $data = $request->validate([
            'body' => ['required', 'string'],
        ]);

        $supportThread->messages()->create([
            'sender_id' => Auth::id(),
            'sender_role' => 'participant',
            'body' => $data['body'],
        ]);

        $supportThread->update([
            'last_activity_at' => now(),
        ]);

        return back();
    }

    protected function authorizeParticipant(SupportThread $supportThread): void
    {
        abort_unless($supportThread->participant_user_id === Auth::id(), 403);
    }
}
