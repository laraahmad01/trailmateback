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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->enum('type', ['like', 'comment', 'mention', 'follow', 'join_request', 'request_accepted', 'request_rejected', 'removed_from_community', 'invited_to_community']);
            $table->foreignId('related_user_id')->nullable()->constrained('users');
            $table->foreignId('related_post_id')->nullable()->constrained('posts');
            $table->foreignId('related_community_id')->nullable()->constrained('communities');
            $table->boolean('is_read');
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
