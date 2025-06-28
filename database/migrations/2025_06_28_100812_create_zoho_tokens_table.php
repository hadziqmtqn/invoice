<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('zoho_tokens', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('zoho_config_id');
            $table->string('access_token');
            $table->string('refresh_token');
            $table->string('api_domain');
            $table->string('token_type');
            $table->integer('expires_in');
            $table->timestamp('expired_at');
            $table->timestamps();

            $table->foreign('zoho_config_id')->references('id')->on('zoho_configs')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('zoho_tokens');
    }
};
