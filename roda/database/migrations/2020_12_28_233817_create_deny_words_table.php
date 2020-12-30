<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Migration auto-generated by TablePlus 3.12.0(354)
 * @author https://tableplus.com
 * @source https://github.com/TablePlus/tabledump
 */
class CreateDenyWordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deny_words', function (Blueprint $table) {
            $table->id();
            $table->string('word', 200);
            $table->datetime('created_at')->default(CURRENT_TIMESTAMP);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('deny_words');
    }
}
