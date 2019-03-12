<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLabelToAddressTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('address_types', function (Blueprint $table) {
            $table->string('name')->after('id');
        });

        Schema::table('address_type_translations', function (Blueprint $table) {
            $table->renameColumn('name', 'label');
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
            $table->dropColumn('name');
        });

        Schema::table('address_type_translations', function (Blueprint $table) {
            $table->renameColumn('label', 'name');
        });
    }
}
