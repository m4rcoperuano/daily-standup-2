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
        Schema::table('stand_up_groups', function (Blueprint $table) {
            $table->string('atlassian_board_id')->nullable();
            $table->string('atlassian_sprint_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stand_up_groups', function (Blueprint $table) {
            $table->dropColumn([
                'atlassian_board_id',
                'atlassian_sprint_id'
            ]);
        });
    }
};
