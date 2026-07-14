<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('audits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade'); // Link to customer login account
            $table->string('business_name');
            $table->string('owner_name');
            $table->string('business_type')->nullable();
            $table->string('industry')->nullable();
            $table->integer('years_in_operation')->nullable();
            $table->integer('no_of_employees')->nullable();
            $table->string('auditor_name')->nullable();
            $table->date('audit_date')->nullable();
            $table->text('additional_notes')->nullable();
            $table->enum('status', ['draft', 'completed'])->default('draft'); // For saved draft features
            $table->integer('total_score')->default(0);
            $table->integer('total_xp')->default(0);
            $table->string('pdf_path')->nullable(); // To store the generated PDF path file
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audits');
    }
};
