<?php

namespace App\Http\Controllers\Support;

use App\Http\Controllers\Controller;
use App\Models\SupportThread;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminSupportController extends Controller
{
    public function index(Request $request)
    {
        $threads = SupportThread::with('participant')
            ->withCount('messages')
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->status))
            ->when($request->filled('type'), fn ($query) => $query->where('type', $request->type))
            ->orderByDesc('last_activity_at')
            ->orderByDesc('created_at')
            ->paginate(12)
            ->withQueryString();

        return view('support.admin.index', [
            'threads' => $threads,
            'statusOptions' => ['open' => __('support.status_open'), 'pending' => __('support.status_pending'), 'resolved' => __('support.status_resolved')],
            'typeOptions' => ['chat' => __('support.type_chat'), 'problem' => __('support.type_problem')],
        ]);
    }

    public function show(SupportThread $supportThread)
    {
        $messages = $supportThread->messages()
            ->with('sender')
            ->orderBy('created_at')
            ->get();

        return view('support.admin.show', compact('supportThread', 'messages'));
    }

    public function storeMessage(Request $request, SupportThread $supportThread)
    {
        $data = $request->validate([
            'body' => ['required', 'string'],
            'status' => ['nullable', 'in:open,pending,resolved'],
        ]);

        $supportThread->messages()->create([
            'sender_id' => Auth::id(),
            'sender_role' => 'admin',
            'body' => $data['body'],
        ]);

        $supportThread->update([
            'last_activity_at' => now(),
            'admin_user_id' => $supportThread->admin_user_id ?? Auth::id(),
            'status' => $data['status'] ?? $supportThread->status,
        ]);

        return back();
    }
}
