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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->longText('description');
            $table->unsignedBigInteger('assign_to');
            $table->unsignedBigInteger('created_by');
            $table->timestamps();
            $table->foreign('assign_to', 'fk_posts_assign_to_users_id')->references('id')->on('users');
            $table->foreign('created_by', 'fk_posts_created_by_users_id')->references('id')->on('users');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tasks', static function (Blueprint $table) {
            $table->dropForeign('fk_posts_assign_to_users_id');
            $table->dropForeign('fk_posts_created_by_users_id');
        });
        Schema::dropIfExists('posts');
    }
};
