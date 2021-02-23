<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('image_id');
            $table->text('comment')->default(null);
            $table->timestamp('created_at')->useCurrent()->index();
            $table->timestamp('updated_at')
                  ->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'))
                  ->index();
            $table->timestamp('deleted_at')->nullable()->index();
            $table->foreign('image_id')->references('id')->on('images');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('comments');
    }
}
