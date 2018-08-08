<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Update01ObjectivesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('objectives', function (Blueprint $table) {
            $table->renameColumn('method', 'name');
            $table->boolean('status')->unsigned()->nullable()->change();
            $table->boolean('is_private')->default(false)->change();
            $table->string('description')->nullable()->change();
            $table->dropColumn('current');
            $table->renameColumn('improgress', 'actual');
            $table->dropColumn('start_date');
            $table->dropColumn('end_date');
            $table->integer('quarter_id')->unsigned()->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('objectives', function (Blueprint $table) {
            $table->renameColumn('name', 'method');
            $table->boolean('status')->unsigned()->nullable(false)->change();
            $table->boolean('is_private')->change();
            $table->string('description')->nullable(false)->change();
            $table->tinyInteger('current')->unsigned();
            $table->renameColumn('actual', 'improgress');
            $table->date('start_date');
            $table->date('end_date');
            $table->dropColumn('quarter_id');
        });
    }
}
