<?php

return [
    'menu' => 'Notifications',
    'title_admin' => 'Activity feed',
    'title_participant' => 'My notifications',

    'actions' => [
        'mark_all_read' => 'Mark all as read',
        'mark_read' => 'Mark as read',
        'view' => 'View',
        'back' => 'Back',
    ],

    'filters' => [
        'search' => 'Search',
        'type' => 'Type',
        'auction' => 'Auction',
        'from' => 'From',
        'to' => 'To',
        'unread_only' => 'Unread only',
        'apply' => 'Apply',
        'reset' => 'Reset',
    ],

    'labels' => [
        'unread' => 'Unread',
        'read' => 'Read',
        'romanian' => 'Romanian',
        'english' => 'English',
        'type' => 'Type',
        'created_at' => 'Created',
        'context' => 'Context',
    ],

    'empty' => 'No notifications yet.',

    'types' => [
        'auction' => [
            'created' => 'Auction created: :auction',
            'updated' => 'Auction updated: :auction',
            'deleted' => 'Auction deleted: :auction',
            'status_changed' => 'Auction status changed: :auction (:from → :to)',
        ],
        'lot' => [
            'created' => 'Lot created: :lot (:auction)',
            'updated' => 'Lot updated: :lot (:auction)',
            'deleted' => 'Lot deleted: :lot (:auction)',
        ],
        'bid' => [
            'created' => 'Offer submitted: :carrier — :lot (:auction)',
            'updated' => 'Offer updated: :carrier — :lot (:auction)',
            'deleted' => 'Offer deleted: :carrier — :lot (:auction)',
            'status_changed' => 'Offer status changed: :carrier — :lot (:auction) (:from → :to)',
            'status_accepted' => 'Your offer was approved: :lot (:auction)',
            'status_rejected' => 'Your offer was disapproved: :lot (:auction)',
        ],
        'document' => [
            'created' => 'Document added: :type (:auction)',
            'updated' => 'Document updated: :type (:auction)',
            'deleted' => 'Document deleted: :type (:auction)',
        ],
    ],
];

