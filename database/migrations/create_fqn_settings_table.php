<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fqn_settings', function (Blueprint $table) {
            $table->increments('id');
            $table->string('fqn')->index();
            $table->string('key')->index();
            $table->string('type')->nullable();
            $table->json('value')->nullable();
            $table->json('default')->nullable();
            $table->boolean('encrypt')->default(false);
            $table->dateTime('lost_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fqn_settings');
    }
};
