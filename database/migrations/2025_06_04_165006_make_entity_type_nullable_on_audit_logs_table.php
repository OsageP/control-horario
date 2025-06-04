<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('audit_logs', function (Blueprint $table) {
            $table->string('entity_type')->nullable()->change();
            $table->unsignedBigInteger('entity_id')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('audit_logs', function (Blueprint $table) {
            $table->string('entity_type')->nullable(false)->change();
            $table->unsignedBigInteger('entity_id')->nullable(false)->change();
        });
    }
};

