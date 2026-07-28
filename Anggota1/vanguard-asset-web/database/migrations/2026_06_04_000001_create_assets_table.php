<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('assets', function (Blueprint $table) {
            $table->string('id')->primary(); // Asset Code (e.g., AST-001, AST-002)
            $table->string('name');
            $table->string('type'); // physical, digital
            $table->string('status')->default('Available'); // Available, Borrowed, Under Maintenance
            $table->decimal('purchase_cost', 15, 2);
            $table->date('purchase_date');
            $table->json('detail_json'); // Stores physical (serial_number, maintenance_interval) or digital (license_key, expiry_date)
            
            // Relationship for tracking current borrower
            $table->unsignedBigInteger('borrowed_by_id')->nullable();
            $table->foreign('borrowed_by_id')->references('id')->on('users')->onDelete('set null');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assets');
    }
};
