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
        Schema::create('options', function (Blueprint $table) {
            $table->id();

            // Polymorphic relationship fields
            $table->unsignedBigInteger('optionable_id');  // This stores the related model's ID (e.g., from user_attributes, respondent_attributes, or questions).
            $table->string('optionable_type');  // This stores the related model's class name (e.g., App\Models\UserAttribute).

            // Other columns for options
            $table->string('display_text');
            $table->string('value');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('options');
    }
};
