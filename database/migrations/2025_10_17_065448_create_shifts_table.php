<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('shifts', function (Blueprint $table) {
            $table->id();
            $table->string('shift_name');
            $table->enum('shift_type', ['morning', 'evening', 'night', 'custom'])->default('custom');
            $table->time('start_time');
            $table->time('end_time');
            $table->integer('max_capacity')->default(1);
            $table->text('description')->nullable();
            $table->string('location')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
            $table->softDeletes();

            $table->index('status');
            $table->index('shift_type');
        });
    }

    public function down(): void {
        Schema::dropIfExists('shifts');
    }
};
