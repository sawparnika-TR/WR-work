<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBankBranchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bank_branches', function (Blueprint $table) {
            $table->bigIncrements('bb_id');
            $table->unsignedBigInteger('bb_bk_id');
            $table->foreign('bb_bk_id')->references('bk_id')->on('banks')->onDelete('cascade');
            $table->string('bb_branch_name');
            $table->string('bb_ifsc');
            $table->enum('status',['0','1'])->default('1')->comment('1=active,0=deactive');
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
        Schema::dropIfExists('bank_branches');
    }
}
