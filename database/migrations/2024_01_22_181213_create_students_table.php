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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('section_id')->constrained('sections');
            $table->foreignId('admin_id')->constrained('admins');
            $table->string('phone_number')->nullable();
            $table->string('parent_name')->nullable();
            $table->string('parent_phone_number')->nullable();
            $table->string('attendance')->default("0/0");
            $table->string('date_of_absence')->nullable();
            

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
