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
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('address')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('website')->nullable();
            $table->longText('description')->nullable();
            $table->string('logo_path')->nullable();
            $table->string('pan_number')->nullable();
            $table->string('pan_document_path')->nullable();
            $table->string('ssf_number')->nullable();
            $table->string('vat_number')->nullable();
            $table->string('vat_document_path')->nullable();
            $table->string('registration_number')->nullable();
            $table->string('registration_document_path')->nullable();
            $table->date('established_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
