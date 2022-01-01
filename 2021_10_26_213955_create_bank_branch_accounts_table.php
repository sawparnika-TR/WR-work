<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBankBranchAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bank_branch_accounts', function (Blueprint $table) {
            $table->bigIncrements('bba_id');
            $table->unsignedBigInteger('bba_bk_id');
            $table->foreign('bba_bk_id')->references('bk_id')->on('banks')->onDelete('cascade');
            $table->unsignedBigInteger('bba_bb_id');
            $table->foreign('bba_bb_id')->references('bb_id')->on('bank_branches')->onDelete('cascade');
            $table->string('bba_ac_no');
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
        Schema::dropIfExists('bank_branch_accounts');
    }
}
