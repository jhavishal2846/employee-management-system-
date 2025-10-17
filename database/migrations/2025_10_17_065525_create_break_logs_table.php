<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('break_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attendance_log_id')->constrained('attendance_logs')->onDelete('cascade');
            $table->timestamp('break_start');
            $table->timestamp('break_end')->nullable();
            $table->integer('duration_minutes')->nullable();
            $table->enum('break_type', ['lunch', 'short', 'other'])->default('short');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index('attendance_log_id');
        });
    }

    public function down(): void {
        Schema::dropIfExists('break_logs');
    }
};
