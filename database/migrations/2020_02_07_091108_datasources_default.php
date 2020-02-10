<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DatasourcesDefault extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('datasources_default', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('table_associated'); //Files, or tickets ,...
            $table->string('query');

            $table->unsignedBigInteger('id_database');
            $table->foreign('id_database')->references('id')->on('databases')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('datasources_default');
    }
}
