<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Update01ActivityLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('activity_logs', function (Blueprint $table) {
            $table->dropColumn('user_type');
            $table->integer('user_id')->unsigned()->change();
            $table->integer('group_id')->unsigned()->after('user_id');
            $table->renameColumn('auditable_type', 'type');
            $table->dropColumn('auditable_id');
            $table->dropColumn('url');
            $table->dropColumn('ip_address');
            $table->dropColumn('user_agent');
            $table->dropColumn('tags');
            $table->index(['user_id', 'group_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('activity_logs', function (Blueprint $table) {
            $table->string('user_type')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('auditable_type');
            $table->integer('auditable_id')->unsigned();
            $table->text('url')->nullable();
            $table->ipAddress('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->string('tags')->nullable();
            $table->index(['user_id', 'user_type']);
        });
    }
}
