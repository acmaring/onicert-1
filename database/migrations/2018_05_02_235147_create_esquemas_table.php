<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEsquemasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('esquemas', function (Blueprint $table) {
            #$table->increments('id');

            // $table->string('esq_id');
            $table->increments('esq_id');
            $table->string('esq_name');
            $table->string('esq_parent')->nullable();
            $table->integer('esq_cant');
            $table->date('esq_vigencia');
            $table->integer('esq_version');
            $table->string('esq_code');

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
        Schema::dropIfExists('esquemas');
    }
}
