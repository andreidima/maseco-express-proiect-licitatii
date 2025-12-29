<?php

namespace App\Http\Controllers;

use App\Models\AppNotification;
use App\Models\NotificationRead;
use App\Models\User;
use App\Models\Ltm\Auction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        /** @var User $user */
        $user = $request->user();
        $reader = AppNotification::readerFor($user);
        $readerKind = $reader['kind'];
        $readerId = $reader['id'];

        $baseQuery = AppNotification::query()->forCurrentUser($user);

        if ($request->boolean('unread') && $readerId > 0) {
            $baseQuery->unreadFor($readerKind, $readerId);
        }

        $baseQuery->when($request->filled('type'), fn ($q) => $q->where('type', (string) $request->string('type')));
        $baseQuery->when($request->filled('auction_id'), fn ($q) => $q->where('auction_id', $request->integer('auction_id')));

        if ($request->filled('from')) {
            $baseQuery->where('created_at', '>=', $request->date('from')->startOfDay());
        }
        if ($request->filled('to')) {
            $baseQuery->where('created_at', '<=', $request->date('to')->endOfDay());
        }

        if ($request->filled('q')) {
            $q = trim((string) $request->string('q'));
            $baseQuery->where(function ($sub) use ($q) {
                $sub->where('context', 'like', '%' . $q . '%')
                    ->orWhere('type', 'like', '%' . $q . '%');
            });
        }

        $types = (clone $baseQuery)
            ->select('type')
            ->whereNotNull('type')
            ->distinct()
            ->orderBy('type')
            ->pluck('type')
            ->all();

        $auctionIds = (clone $baseQuery)
            ->select('auction_id')
            ->whereNotNull('auction_id')
            ->distinct()
            ->pluck('auction_id')
            ->filter()
            ->values();

        $auctions = Auction::query()
            ->when($auctionIds->isNotEmpty(), fn ($q) => $q->whereIn('id', $auctionIds))
            ->orderBy('auction_number')
            ->get(['id', 'auction_number', 'title']);

        $query = (clone $baseQuery)
            ->withReadState($readerKind, $readerId)
            ->with(['actor:id,name'])
            ->orderByDesc('id');

        $notifications = $query
            ->paginate(20)
            ->appends($request->query());

        return view('notifications.index', [
            'notifications' => $notifications,
            'types' => $types,
            'auctions' => $auctions,
            'filters' => $request->all(),
            'isParticipant' => ($user->role ?? null) === 'Participant licitatii',
            'readerKind' => $readerKind,
            'readerId' => $readerId,
        ]);
    }

    public function show(Request $request, AppNotification $notification)
    {
        /** @var User $user */
        $user = $request->user();
        $this->authorizeView($notification, $user);

        $reader = AppNotification::readerFor($user);
        if ($reader['id'] > 0) {
            $notification->markRead($reader['kind'], $reader['id']);
        }

        $notification->loadMissing(['actor:id,name']);

        return view('notifications.show', [
            'notification' => $notification,
            'isParticipant' => ($user->role ?? null) === 'Participant licitatii',
        ]);
    }

    public function markRead(Request $request, AppNotification $notification)
    {
        /** @var User $user */
        $user = $request->user();
        $this->authorizeView($notification, $user);

        $reader = AppNotification::readerFor($user);
        if ($reader['id'] > 0) {
            $notification->markRead($reader['kind'], $reader['id']);
        }

        return back();
    }

    public function markAllRead(Request $request)
    {
        /** @var User $user */
        $user = $request->user();
        $reader = AppNotification::readerFor($user);
        $readerKind = $reader['kind'];
        $readerId = $reader['id'];

        if ($readerId <= 0) {
            return back();
        }

        $notificationIds = AppNotification::query()
            ->forCurrentUser($user)
            ->unreadFor($readerKind, $readerId)
            ->orderByDesc('id')
            ->limit(2000)
            ->pluck('id')
            ->all();

        if ($notificationIds === []) {
            return back();
        }

        $now = now();
        $rows = array_map(fn ($id) => [
            'notification_id' => $id,
            'reader_kind' => $readerKind,
            'reader_id' => $readerId,
            'read_at' => $now,
            'created_at' => $now,
            'updated_at' => $now,
        ], $notificationIds);

        DB::transaction(function () use ($rows) {
            NotificationRead::query()->upsert(
                $rows,
                ['notification_id', 'reader_kind', 'reader_id'],
                ['read_at', 'updated_at']
            );
        });

        return back();
    }

    private function authorizeView(AppNotification $notification, User $user): void
    {
        $isParticipant = ($user->role ?? null) === 'Participant licitatii';

        if ($isParticipant) {
            abort_unless(
                $notification->audience === 'carrier' && (int) $notification->carrier_id === (int) $user->carrier_id,
                403
            );

            return;
        }

        abort_unless($notification->audience === 'admin', 403);
    }
}
