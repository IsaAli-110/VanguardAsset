<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('security_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('event_type');          // login_success, login_failed, logout, asset_created, asset_deleted, role_changed, etc.
            $table->string('severity')->default('info'); // info, warning, critical
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->string('target_type')->nullable();   // App\Models\Asset, App\Models\User, etc.
            $table->string('target_id')->nullable();
            $table->json('metadata')->nullable();        // Extra context data
            $table->text('description');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->index(['event_type', 'created_at']);
            $table->index(['user_id', 'created_at']);
            $table->index('ip_address');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('security_logs');
    }
};
