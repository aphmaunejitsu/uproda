<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Migration auto-generated by TablePlus 3.5.3(314)
 * @author https://tableplus.com
 * @source https://github.com/TablePlus/tabledump
 */
class CreateImageHashTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('image_hashes', function (Blueprint $table) {
            $table->bigInteger('id')->autoIncrement();
            $table->string('hash', 256);
            $table->text('comment')->nullable();
            $table->tinyInteger('ng')->nullable()->default(0);
            $table->timestamp('created_at')->useCurrent()->index('idx_created_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('image_hashes');
    }
}
