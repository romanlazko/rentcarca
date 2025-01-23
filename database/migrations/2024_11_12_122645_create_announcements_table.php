<?php

use App\Enums\Status;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Romanlazko\Telegram\Models\TelegramChat;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('announcements', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(TelegramChat::class)->constrained()->cascadeOnDelete();
            $table->string('model')->nullable();
            $table->string('city')->nullable();
            $table->string('body_type')->nullable();
            $table->string('transmission')->nullable();
            $table->string('drive_type')->nullable();
            $table->string('fuel_type')->nullable();
            $table->string('mileage')->nullable();
            $table->json('features')->nullable();
            $table->string('price')->nullable();
            $table->string('deposit')->nullable();
            $table->string('lease_term')->nullable();
            $table->json('photos')->nullable();

            $table->boolean('paid')->nullable()->default(false);
            $table->integer('status')->nullable()->default(Status::created);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('announcements');
    }
};
