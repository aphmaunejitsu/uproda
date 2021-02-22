<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddForienkeyToImageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('images', function (Blueprint $table) {
            $table->foreign('image_hash_id')->references('id')->on('image_hashes');
            $table->timestamp('updated_at')
                  ->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'))
                  ->index()->after('created_at');
            $table->timestamp('deleted_at')->nullable()->index()->after('updated_at');
            $table->string('mimetype', 100)->nullable()->change();
        });

        DB::statement('ALTER TABLE `images` MODIFY `created_at` timestamp NOT NULL DEFAULT current_timestamp');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('images', function (Blueprint $table) {
            $table->dropForeign(['image_hash_id']);
            $table->dateTime('created_at')->change();
            $table->dropColumn('updated_at');
            $table->dropColumn('deleted_at');
        });
    }
}
