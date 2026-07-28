<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('approval_requests', function (Blueprint $table) {
            $table->timestamp('borrowed_at')->nullable()->after('reason');
            $table->date('due_date')->nullable()->after('borrowed_at');
            $table->timestamp('returned_at')->nullable()->after('due_date');
            $table->text('reject_reason')->nullable()->after('returned_at');
            $table->unsignedBigInteger('reviewed_by')->nullable()->after('reject_reason');
        });
    }

    public function down(): void
    {
        Schema::table('approval_requests', function (Blueprint $table) {
            $table->dropColumn(['borrowed_at', 'due_date', 'returned_at', 'reject_reason', 'reviewed_by']);
        });
    }
};
