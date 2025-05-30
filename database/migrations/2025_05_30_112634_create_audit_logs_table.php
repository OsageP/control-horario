<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // A quién afecta
            $table->foreignId('actor_id')->nullable()->constrained('users')->onDelete('set null'); // Quién lo hizo
            $table->string('action'); // 'assigned role', 'revoked permission', etc.
            $table->string('target_model')->nullable(); // Modelo afectado (User, Role, etc)
            $table->unsignedBigInteger('target_id')->nullable(); // ID del modelo afectado
            $table->json('changes')->nullable(); // Detalles del cambio
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};
