<?php

return [
    'title' => 'Database migrations',
    'subtitle' => 'Monitor which migrations have already run and which changes are about to be applied.',
    'next_batch' => 'Next batch: :batch',

    'status_updated' => 'Migration status updated.',
    'view_command_log' => 'View command log',

    'cards_total' => 'Total migrations',
    'cards_total_hint' => 'Files found in the project.',
    'cards_ran' => 'Migrations ran',
    'cards_ran_hint' => 'Last batch ran: :batch',
    'cards_pending' => 'Pending migrations',
    'cards_pending_hint' => 'Review details before running.',

    'pending_title' => 'Pending migrations',
    'pretend_failed' => 'SQL preview could not be generated.',
    'none_pending' => 'There are no migrations waiting to run.',
    'pending_explainer' => 'These migrations will run in batch <strong>:batch</strong>. The SQL below comes from running <code>php artisan migrate --pretend</code>.',
    'file' => 'File',
    'estimated_sql' => 'Estimated SQL',
    'no_sql_preview' => 'No SQL preview is available for this migration.',

    'run_with_care_title' => 'Run migrations carefully',
    'run_with_care_body' => 'Make sure you have a recent backup and you understand the changes above before continuing. This action runs <code>php artisan migrate --force</code> in production.',
    'confirm_run_label' => 'I confirm I reviewed the preview and I have an updated backup.',
    'run_now' => 'Run migrations now',
    'after_run_hint' => 'After running, the command result and any errors will appear above on this page.',

    'ran_title' => 'Migrations already ran',
    'none_ran' => 'No migration has been run yet.',
    'col_batch' => 'Batch',
    'col_migration' => 'Migration',
    'col_actions' => 'Actions',
    'undo' => 'Rollback',
    'confirm_undo' => 'Are you sure you want to run down for this migration?',

    'validation_confirm_run' => 'Confirm that you understand the risks to continue.',

    'status_ran_success' => 'Migrations were executed.',
    'status_run_error' => 'An error occurred while running migrations: :message',
    'status_no_migrations_table' => 'The "migrations" table does not exist. No migration can be rolled back.',
    'status_migration_file_missing' => 'The selected migration file was not found.',
    'status_migration_not_ran' => 'The selected migration does not appear to have been run.',
    'status_rollback_error' => 'The rollback command returned an error. Check the log for details.',
    'status_rollback_success' => 'The migration was rolled back successfully.',
    'status_rollback_failed' => 'Rollback failed: :message',
];
