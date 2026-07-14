<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g., Finance & Accounting, Operations, etc.
            $table->text('description')->nullable();
            $table->string('icon')->nullable(); // For storing icon CSS classes or image names
            $table->integer('sort_order')->default(0); // For keeping steps in order
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
