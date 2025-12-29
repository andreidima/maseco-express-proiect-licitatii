<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->string('audience', 16)->index(); // admin | carrier
            $table->unsignedBigInteger('carrier_id')->nullable()->index();
            $table->unsignedBigInteger('actor_user_id')->nullable()->index();

            $table->unsignedBigInteger('auction_id')->nullable()->index();
            $table->unsignedBigInteger('lot_id')->nullable()->index();
            $table->unsignedBigInteger('bid_id')->nullable()->index();
            $table->unsignedBigInteger('document_id')->nullable()->index();

            $table->string('type', 100)->index();
            $table->string('context')->nullable()->index();
            $table->json('data')->nullable();
            $table->timestamps();
        });

        Schema::create('notification_reads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('notification_id')
                ->constrained('notifications')
                ->cascadeOnDelete();

            $table->string('reader_kind', 16); // user | carrier
            $table->unsignedBigInteger('reader_id');
            $table->timestamp('read_at');
            $table->timestamps();

            $table->unique(['notification_id', 'reader_kind', 'reader_id'], 'notification_reads_unique_reader');
            $table->index(['reader_kind', 'reader_id', 'read_at'], 'notification_reads_reader_lookup');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notification_reads');
        Schema::dropIfExists('notifications');
    }
};

