<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableExpensePesertas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expense_pesertas', function (Blueprint $table) {
            $table->id();
            $table->integer('expense_id')->nullable();
            $table->string('no_peserta',100)->nullable();
            $table->string('nama_peserta',255)->nullable();
            $table->boolean('type')->nullable()->comment = "1=Claim Payable, etc";
            $table->integer('policy_id')->nullable();
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
        Schema::dropIfExists('expense_pesertas');
    }
}
