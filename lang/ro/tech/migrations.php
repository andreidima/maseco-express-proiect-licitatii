<?php

return [
    'title' => 'Migrații bază de date',
    'subtitle' => 'Monitorizează ce migrații au rulat deja și ce schimbări urmează să fie aplicate.',
    'next_batch' => 'Următorul batch: :batch',

    'status_updated' => 'Status migrații actualizat.',
    'view_command_log' => 'Vezi jurnalul comenzii',

    'cards_total' => 'Total migrații',
    'cards_total_hint' => 'Fișiere găsite în proiect.',
    'cards_ran' => 'Migrații rulate',
    'cards_ran_hint' => 'Ultimul batch rulat: :batch',
    'cards_pending' => 'Migrații în așteptare',
    'cards_pending_hint' => 'Revizuiește detaliile înainte de rulare.',

    'pending_title' => 'Migrații în așteptare',
    'pretend_failed' => 'Previzualizarea SQL nu a putut fi generată.',
    'none_pending' => 'Nu există migrații care așteaptă să fie rulate.',
    'pending_explainer' => 'Aceste migrații vor rula în batch-ul <strong>:batch</strong>. SQL-ul de mai jos provine din rularea comenzii <code>php artisan migrate --pretend</code>.',
    'file' => 'Fișier',
    'estimated_sql' => 'SQL estimat',
    'no_sql_preview' => 'Nu există o previzualizare SQL disponibilă pentru această migrație.',

    'run_with_care_title' => 'Rulează migrațiile cu grijă',
    'run_with_care_body' => 'Asigură-te că există un backup recent și că ai înțeles schimbările de mai sus înainte de a continua. Această acțiune rulează <code>php artisan migrate --force</code> în producție.',
    'confirm_run_label' => 'Confirm că am verificat previzualizarea și am un backup actualizat.',
    'run_now' => 'Rulează migrațiile acum',
    'after_run_hint' => 'După rulare, rezultatul comenzii și eventualele erori vor apărea mai sus în această pagină.',

    'ran_title' => 'Migrații deja rulate',
    'none_ran' => 'Încă nu a fost rulată nicio migrație.',
    'col_batch' => 'Batch',
    'col_migration' => 'Migrație',
    'col_actions' => 'Acțiuni',
    'undo' => 'Anulează',
    'confirm_undo' => 'Sigur vrei să rulezi down pentru această migrație?',

    'validation_confirm_run' => 'Confirmă că ai înțeles riscurile pentru a continua.',

    'status_ran_success' => 'Migrațiile au fost executate.',
    'status_run_error' => 'A apărut o eroare la rularea migrațiilor: :message',
    'status_no_migrations_table' => 'Nu există tabelul "migrations". Nicio migrație nu poate fi anulată.',
    'status_migration_file_missing' => 'Fișierul pentru migrația selectată nu a fost găsit.',
    'status_migration_not_ran' => 'Migrația selectată nu apare ca fiind rulată deja.',
    'status_rollback_error' => 'Comanda de rollback a returnat o eroare. Verifică jurnalul pentru detalii.',
    'status_rollback_success' => 'Migrația a fost rulată înapoi cu succes.',
    'status_rollback_failed' => 'Anularea migrației a eșuat: :message',
];
