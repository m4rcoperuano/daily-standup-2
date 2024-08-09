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
        Schema::create('socialite_integrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            $table->string('provider');
            $table->text('access_token');
            $table->text('refresh_token')->nullable();

            $table->string('provider_user_name', 500)->nullable();
            $table->text('provider_user_avatar')->nullable();
            $table->string('provider_user_email', 500)->nullable();
            $table->string('provider_user_nick_name', 500)->nullable();
            $table->string('provider_user_id', 500)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('socialite_integrations');
    }
};
