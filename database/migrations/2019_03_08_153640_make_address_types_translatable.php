<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MakeAddressTypesTranslatable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('address_types', function (Blueprint $table) {
            $table->dropColumn('name');
        });

        Schema::create('address_type_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('type_id');
            $table->string('name');
            $table->string('locale')->index();

            $table->unique(['type_id','locale']);

            $table->foreign('type_id')
                ->references('id')
                ->on('address_types')
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
        Schema::table('address_types', function (Blueprint $table) {
            $table->string('name');
        });

        Schema::dropIfExists('address_type_translations');
    }
}
