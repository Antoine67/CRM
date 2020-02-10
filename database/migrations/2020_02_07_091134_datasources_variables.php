<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DatasourcesVariables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('datasources_variables', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('value');
            
			$table->unsignedBigInteger('id_datasource_variable_definition');
            $table->foreign('id_datasource_variable_definition')->references('id')->on('datasources_variables_definition')->onDelete('cascade');
			
			$table->unsignedBigInteger('id_customer');
            $table->foreign('id_customer')->references('id')->on('customers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('datasources_variables');
    }
}
