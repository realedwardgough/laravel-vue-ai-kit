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
        
        /**
         * Only create the chat table if the users table is
         * already in existence within the framework.
         */
        if (Schema::hasTable('users')) {
            
            /**
             * Create the initial schema for the chat table, this will host
             * the main chats. The messages will be stored in a seperate table.
             */
            Schema::create('chats', function (Blueprint $table) {
                $table->increments('id');
                $table->foreignId('user_id')->constrained(
                    'users', 'user_id',
                );
                $table->timestamps();
            });
 
            /**
             * The messages table will link to a single chat, but house all
             * the indiviual messages.
             */
            Schema::create('messages', function (Blueprint $table) {
                $table->increments('id');
                $table->foreignId('chat_id')->constrained(
                    'chats','chat_id',
                );
                $table->longText('content');
                $table->integer('user_or_model');
                $table->timestamps();
            });

        }

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chats');
        Schema::dropIfExists('messages');
    }
};
