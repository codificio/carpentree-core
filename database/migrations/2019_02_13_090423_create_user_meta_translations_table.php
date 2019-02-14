<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserMetaTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_meta_translations', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('user_meta_id');
            $table->string('value');
            $table->string('locale')->index();

            $table->unique(['user_meta_id','locale']);

            $table->foreign('user_meta_id')
                ->references('id')
                ->on('user_meta')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });

        Schema::table('user_meta', function (Blueprint $table) {
            $table->dropColumn('value');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_meta_translations');

        Schema::table('user_meta', function (Blueprint $table) {
            $table->string('value')->after('key');
        });
    }
}
