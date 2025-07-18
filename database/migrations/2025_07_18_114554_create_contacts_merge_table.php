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
        Schema::create('contacts_merge', function (Blueprint $table) {
            $table->uuid('id')->primary(); 
            $table->uuid('contact_uuid');
            $table->uuid('contact_child_uuid');
            $table->string('email')->nullable();
            $table->string('Phone')->nullable();
            $table->json('custom_field')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('contact_uuid')
            ->references('id')
            ->on('contact')
            ->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contacts_merge');
    }
};
