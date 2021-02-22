<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class ModifyTypeToDenayIpsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('deny_ips', function (Blueprint $table) {
            $table->timestamp('updated_at')
                  ->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'))
                  ->index()->after('created_at');
            $table->timestamp('deleted_at')->nullable()->index()->after('updated_at');
            $table->index('ip');
        });
        DB::statement('ALTER TABLE `deny_ips` MODIFY `created_at` timestamp NOT NULL DEFAULT current_timestamp');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('deny_ips', function (Blueprint $table) {
            $table->dateTime('created_at')->change();
            $table->dropColumn('updated_at');
            $table->dropColumn('deleted_at');
            $table->dropIndex(['ip']);
        });
    }
}
