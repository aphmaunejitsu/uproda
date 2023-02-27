<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterChunksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('chunk_files', function (Blueprint $table) {
            $table->string('ip', 40)->nullable()->after('uuid');
            $table->string('original', 128)->nullable()->after('ip');
            $table->integer('number')->default(0)->after('original');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('chunk_files', function (Blueprint $table) {
            $table->dropColumn('ip');
            $table->dropColumn('original');
            $table->dropColumn('number');
        });
    }
}
