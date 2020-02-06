<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->bigIncrements('id');
			$table->string('name')->default('Aucun nom défini');
			$table->string('email')->nullable();
			$table->string('phone')->nullable();
            $table->string('picture')->nullable();
            $table->string('sharepoint_client', 1000)->nullable();
            $table->string('sharepoint_extranet', 1000)->nullable();
            $table->string('web_url', 1000)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customers');
    }
}
