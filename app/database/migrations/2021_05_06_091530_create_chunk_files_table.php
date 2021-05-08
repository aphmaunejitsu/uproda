<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateChunkFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chunk_files', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique()->comment('uuid');
            $table->boolean('is_uploaded')->default(false)->index()->comment('upload success');
            $table->boolean('is_fail')->default(false)->index()->comment('upload fail');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')
                  ->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('chunk_files');
    }
}
