<?php

return [
    'menu' => 'Notificări',
    'title_admin' => 'Activitate',
    'title_participant' => 'Notificările mele',

    'actions' => [
        'mark_all_read' => 'Marchează toate ca citite',
        'mark_read' => 'Marchează ca citit',
        'view' => 'Vezi',
        'back' => 'Înapoi',
    ],

    'filters' => [
        'search' => 'Căutare',
        'type' => 'Tip',
        'auction' => 'Licitație',
        'from' => 'De la',
        'to' => 'Până la',
        'unread_only' => 'Doar necitite',
        'apply' => 'Aplică',
        'reset' => 'Resetează',
    ],

    'labels' => [
        'unread' => 'Necitit',
        'read' => 'Citit',
        'romanian' => 'Română',
        'english' => 'Engleză',
        'type' => 'Tip',
        'created_at' => 'Creat',
        'context' => 'Context',
    ],

    'empty' => 'Nu există notificări încă.',

    'types' => [
        'auction' => [
            'created' => 'Licitație creată: :auction',
            'updated' => 'Licitație actualizată: :auction',
            'deleted' => 'Licitație ștearsă: :auction',
            'status_changed' => 'Status licitație schimbat: :auction (:from → :to)',
        ],
        'lot' => [
            'created' => 'Lot creat: :lot (:auction)',
            'updated' => 'Lot actualizat: :lot (:auction)',
            'deleted' => 'Lot șters: :lot (:auction)',
        ],
        'bid' => [
            'created' => 'Ofertă adăugată: :carrier — :lot (:auction)',
            'updated' => 'Ofertă modificată: :carrier — :lot (:auction)',
            'deleted' => 'Ofertă ștearsă: :carrier — :lot (:auction)',
            'status_changed' => 'Status ofertă schimbat: :carrier — :lot (:auction) (:from → :to)',
            'status_accepted' => 'Oferta ta a fost aprobată: :lot (:auction)',
            'status_rejected' => 'Oferta ta a fost respinsă: :lot (:auction)',
        ],
        'document' => [
            'created' => 'Document adăugat: :type (:auction)',
            'updated' => 'Document actualizat: :type (:auction)',
            'deleted' => 'Document șters: :type (:auction)',
        ],
    ],
];

