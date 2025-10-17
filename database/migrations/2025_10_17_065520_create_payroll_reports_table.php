<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('payroll_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('users')->onDelete('cascade');
            $table->date('period_start');
            $table->date('period_end');
            $table->decimal('total_hours', 8, 2);
            $table->decimal('regular_hours', 8, 2);
            $table->decimal('overtime_hours', 8, 2)->default(0.00);
            $table->decimal('hourly_rate', 8, 2);
            $table->decimal('regular_pay', 10, 2);
            $table->decimal('overtime_pay', 10, 2)->default(0.00);
            $table->decimal('deductions', 10, 2)->default(0.00);
            $table->decimal('total_pay', 10, 2);
            $table->integer('days_worked');
            $table->decimal('attendance_rate', 5, 2);
            $table->enum('status', ['draft', 'generated', 'paid'])->default('draft');
            $table->enum('payment_status', ['pending', 'processing', 'paid', 'failed'])->default('pending');
            $table->timestamp('paid_at')->nullable();
            $table->foreignId('generated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['employee_id', 'period_start', 'period_end']);
            $table->index('status');
            $table->index('payment_status');
        });
    }

    public function down(): void {
        Schema::dropIfExists('payroll_reports');
    }
};
