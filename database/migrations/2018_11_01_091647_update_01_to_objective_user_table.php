<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Update01ToObjectiveUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('objective_user', function (Blueprint $table) {
            $table->renameColumn('progress', 'type')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('objective_user', function (Blueprint $table) {
            $table->renameColumn('type', 'progress')->change();
        });
    }
}
