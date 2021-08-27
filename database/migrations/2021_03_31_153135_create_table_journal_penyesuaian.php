<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableJournalPenyesuaian extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('journal_penyesuaians', function (Blueprint $table) {
            $table->id();
            $table->string('journal_no_voucher',255)->nullable();
            $table->integer('coa_id')->nullable();
            $table->string('no_voucher',255)->nullable();
            $table->date('date_journal')->nullable();
            $table->integer('debit')->nullable();
            $table->integer('kredit')->nullable();
            $table->integer('saldo')->nullable();
            $table->boolean('type_journal')->nullable();
            $table->text('description')->nullable();
            $table->boolean('type')->nullable();
            $table->integer('transaction_id')->nullable();
            $table->string('transaction_table',255)->nullable();
            $table->string('transaction_number',255)->nullable();
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
        Schema::dropIfExists('journal_penyesuaians');
    }
}
