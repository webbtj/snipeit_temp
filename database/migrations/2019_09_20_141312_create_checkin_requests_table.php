<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCheckinRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('checkin_requests', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('requestable_id');
            $table->string('requestable_type');
            $table->integer('quantity')->default(1);
            $table->timestamps();

            $table->dateTime('canceled_at')->nullable()->default(null);
            $table->dateTime('fulfilled_at')->nullable()->default(null);
            $table->dateTime('deleted_at')->nullable()->default(null);

            $table->index(['user_id','requestable_id','requestable_type'], 'checkin_requests_user_id_requestable_id_requestable_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('checkin_requests');
    }
}
