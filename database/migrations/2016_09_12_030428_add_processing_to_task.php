<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddProcessingToTask extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('tasks', function(Blueprint $table) {
            $table->integer('processed')->null(false)->default(0)->after('status');
            $table->timestamp('processed_at')->nullable()->after('processed');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::table('tasks', function(Blueprint $table) {
            $table->dropColumn(['processed', 'processed_at']);
        });
    }
}
