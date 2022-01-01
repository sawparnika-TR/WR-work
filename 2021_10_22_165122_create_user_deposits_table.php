<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserDepositsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_deposits', function (Blueprint $table) {
            $table->bigIncrements('ud_id');
            $table->unsignedBigInteger('ud_us_id');
            $table->foreign('ud_us_id')->references('us_id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('ud_bank_id');
            $table->foreign('ud_bank_id')->references('bk_id')->on('banks')->onDelete('cascade');
            $table->date('ud_deposit_date');
            $table->string('ud_amount');
            $table->unsignedBigInteger('ud_bb_id');
            $table->foreign('ud_bb_id')->references('bb_id')->on('bank_branches')->onDelete('cascade');
            $table->enum('ud_approved_status',['0','1'])->default('0')->comment('1=active,0=deactive');
            $table->date('ud_approved_date');
            $table->string('ud_approved_by');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_deposits');
    }
}
