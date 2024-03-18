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
        Schema::create('live_streamings', function (Blueprint $table) {
            $table->id(); /* auto increment id */
            $table->unsignedBigInteger('stream_id')->nullable()->index();
            $table->unsignedBigInteger('user_id')->nullable()->index(); /* Authenticated user id */
            $table->string('wowza_id')->nullable()->index();
            $table->string('stream_title')->nullable();
            $table->text('description')->nullable();
            $table->string('state')->nullable()->index();
            $table->string('billing_mode')->nullable();
            $table->string('broadcast_location')->nullable();
            $table->boolean('recording')->default(0); /* for true or false value */
            $table->string('encoder')->nullable();
            $table->string('delivery_method')->nullable();
            $table->string('sdp_url')->nullable();
            $table->string('application_name')->nullable();
            $table->string('stream_name')->nullable();
            $table->string('hls_playback_url')->nullable();
            $table->string('stream_price')->nullable();
            $table->string('price_currency')->nullable();
            $table->string('image')->nullable();
            $table->string('player_id')->nullable();
            $table->date('stream_date')->nullable();
     	    $table->time('stream_time')->nullable();
  	        $table->boolean('stream_status')->default(0)->index(); /* for true or false value */
	        $table->boolean('advertisement_status')->default(0)->index(); /* for true or false value */
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('live_streamings');
    }
};
