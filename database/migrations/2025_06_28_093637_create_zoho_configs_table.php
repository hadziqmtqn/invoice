<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('zoho_configs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('organization_id')->unique();
            $table->enum('grant_type', ['authorization_code', 'refresh_token']);
            $table->string('code')->nullable();
            $table->string('client_id');
            $table->string('client_secret');
            $table->string('redirect_url');
            $table->string('refresh_token')->nullable();
            $table->timestamps();

            $table->foreign('organization_id')->references('id')->on('organizations')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('zoho_configs');
    }
};
