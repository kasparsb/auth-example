<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->string('hash')->unique();

            // Tablet, admin utt
            $table->string('type')->nullable();

            // Optional name for account
            $table->string('name')->nullable();
            /**
             * Kods, kurš rādās uz planšetes, kad tiek pieprasīta atļauja
             * admin pēc šī koda noteiks vai tā ir tā planšete
             */
            $table->string('request_code')->unique()->nullable();

            $table->datetime('approved_at')->nullable();

            // Laiks, kad account ir online
            $table->datetime('online_at')->nullable();
            $table->datetime('offline_at')->nullable();

            // User agent
            $table->string('user_agent')->nullable();
            $table->json('device_info')->nullable();

            // Any kind of info
            $table->json('info')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('accounts');
    }
};
