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
            $table->id('stream_id'); /* auto increment id */
            $table->integer('user_id')->default(0); /* Authenticated user id */
            $table->string('wowza_id')->default(null);
            $table->string('stream_title')->default(null);
            $table->string('description')->default(null);
            $table->string('state');
            $table->string('billing_mode')->default(null);
            $table->string('broadcast_location')->default(null);
            $table->boolean('recording')->default(0); /* for true or false value */
            $table->string('encoder')->default(null);
            $table->string('delivery_method')->default(null);
            $table->string('sdp_url')->default(null);
            $table->string('application_name')->default(null);
            $table->string('stream_name')->default(null);
            $table->string('hls_playback_url')->default(null);
            $table->string('stream_price')->default(null);
            $table->string('price_currency')->default(null);
            $table->string('image')->default(null);
            $table->string('player_id')->default(null);
            $table->date('stream_date')->default(null);
     	    $table->time('stream_time')->default(null);
  	        $table->boolean('stream_status')->default(0); /* for true or false value */
	        $table->boolean('advertisement_status')->default(0); /* for true or false value */
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
