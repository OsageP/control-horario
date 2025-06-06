<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('audit_logs')) {
            Schema::create('audit_logs', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->foreignId('actor_id')->nullable()->constrained('users')->onDelete('set null');
                $table->string('action');
                $table->string('entity_type')->nullable();  // Añadido para compatibilidad
                $table->unsignedBigInteger('entity_id')->nullable();  // Añadido para compatibilidad
                $table->json('old_values')->nullable();  // Añadido para compatibilidad
                $table->json('new_values')->nullable();  // Añadido para compatibilidad
                $table->string('ip_address')->nullable();  // Añadido para compatibilidad
                $table->string('user_agent')->nullable();  // Añadido para compatibilidad
                $table->string('url')->nullable();  // Añadido para compatibilidad
                $table->string('method')->nullable();  // Añadido para compatibilidad
                $table->string('target_model')->nullable();
                $table->unsignedBigInteger('target_id')->nullable();
                $table->json('changes')->nullable();
                $table->timestamps();
                
                $table->index(['entity_type', 'entity_id']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};