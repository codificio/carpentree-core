<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMetaFieldsTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('meta_fields_translations', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('meta_field_id');

            $table->string('value');
            $table->string('locale')->index();

            $table->unique(['meta_field_id','locale']);

            $table->foreign('meta_field_id')
                ->references('id')
                ->on('meta_fields')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('meta_fields_translations');
    }
}
