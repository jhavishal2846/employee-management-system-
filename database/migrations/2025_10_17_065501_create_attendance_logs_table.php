<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('attendance_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('shift_id')->nullable()->constrained('shifts')->onDelete('set null');
            $table->date('attendance_date');
            $table->timestamp('login_time')->nullable();
            $table->timestamp('logout_time')->nullable();
            $table->integer('total_hours_minutes')->nullable();
            $table->decimal('total_hours', 5, 2)->nullable();
            $table->integer('break_duration_minutes')->default(0);
            $table->enum('status', ['present', 'absent', 'late', 'early_leave', 'on_break'])->default('present');
            $table->boolean('is_overtime')->default(false);
            $table->decimal('overtime_hours', 5, 2)->default(0.00);
            $table->text('notes')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->boolean('is_manual_entry')->default(false);
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();

            $table->index(['employee_id', 'attendance_date']);
            $table->index('attendance_date');
            $table->index('status');
            $table->unique(['employee_id', 'attendance_date']);
        });
    }

    public function down(): void {
        Schema::dropIfExists('attendance_logs');
    }
};
