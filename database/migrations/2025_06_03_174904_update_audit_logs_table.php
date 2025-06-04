<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('audit_logs', function (Blueprint $table) {
            $table->text('url')->nullable()->after('user_agent');
            $table->string('method', 10)->nullable()->after('url');

            $table->index('entity_type');
            $table->index('entity_id');
            $table->index('action');
            $table->index('actor_id');
            $table->index('created_at');
        });
    }

    public function down()
    {
        Schema::table('audit_logs', function (Blueprint $table) {
            $table->dropColumn(['url', 'method']);
            $table->dropIndex(['audit_logs_entity_type_index']);
            $table->dropIndex(['audit_logs_entity_id_index']);
            $table->dropIndex(['audit_logs_action_index']);
            $table->dropIndex(['audit_logs_actor_id_index']);
            $table->dropIndex(['audit_logs_created_at_index']);
        });
    }
};


