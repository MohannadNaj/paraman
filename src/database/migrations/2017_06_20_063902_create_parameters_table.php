<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateParametersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('parameters')->create('parameters', function (Blueprint $table) {
            $table->increments('id');

            $table->string('name');

            $table->string('label')->nullable();

            $table->string('type');

            $table->boolean('is_category')->default(false);

            $table->integer('category_id')->nullable();

            $table->text('value')->nullable();
            $table->text('meta')->nullable();

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
        Schema::connection('parameters')->dropIfExists('parameters');
    }
}
