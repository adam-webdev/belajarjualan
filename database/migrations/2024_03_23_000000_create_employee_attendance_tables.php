<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Create employees table
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('employee_id')->unique();
            $table->string('position');
            $table->string('department');
            $table->date('join_date');
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
            $table->softDeletes();
        });

        // Create monthly_attendance table
        Schema::create('monthly_attendance', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->onDelete('cascade');
            $table->year('year');
            $table->month('month');
            $table->json('attendance_data'); // Will store attendance data for each day
            $table->integer('total_present')->default(0);
            $table->integer('total_absent')->default(0);
            $table->integer('total_sick')->default(0);
            $table->integer('total_leave')->default(0);
            $table->timestamps();

            // Add unique constraint to prevent duplicate entries
            $table->unique(['employee_id', 'year', 'month']);
        });

        // Create attendance_logs table for detailed tracking
        Schema::create('attendance_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->onDelete('cascade');
            $table->date('date');
            $table->enum('status', ['present', 'absent', 'sick', 'leave'])->default('absent');
            $table->time('check_in')->nullable();
            $table->time('check_out')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            // Add unique constraint to prevent duplicate entries
            $table->unique(['employee_id', 'date']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('attendance_logs');
        Schema::dropIfExists('monthly_attendance');
        Schema::dropIfExists('employees');
    }
};