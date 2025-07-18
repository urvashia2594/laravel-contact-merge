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
        Schema::create('contact', function (Blueprint $table) {
            $table->uuid('id')->primary(); 
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('Phone')->nullable();
            $table->tinyInteger('gender')->nullable()->comment('1:male, 2:female, 3:other');
            $table->string('profile_image')->nullable();
            $table->string('doc')->nullable();
            $table->json('custom_field')->nullable();
            $table->tinyInteger('is_master')->nullable()->default(1)->comment('1:master, 0:secondary contact');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contact');
    }
};
