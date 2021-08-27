<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableSyariahReinsurance extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('syariah_reinsurance', function (Blueprint $table) {
            $table->id();
            $table->integer('parent_id')->nullable();
            $table->boolean('is_temp')->default(0)->nullable();
            $table->string('bulan',5)->nullable();
            $table->string('no_polis',50)->nullable();
            $table->string('pemegang_polis',100)->nullable();
            $table->string('peserta',10)->nullable();
            $table->bigInteger('nilai_manfaat_asuransi_total')->nullable();
            $table->bigInteger('nilai_manfaat_asuransi_or')->nullable();
            $table->bigInteger('nilai_manfaat_asuransi_reas')->nullable();
            $table->bigInteger('kontribusi_ajri')->nullable();
            $table->bigInteger('kontribusi_reas_gross')->nullable();
            $table->integer('ujroh')->nullable();
            $table->integer('em')->nullable();
            $table->integer('kontribusi_reas_netto')->nullable();
            $table->text('keterangan')->nullable();
            $table->string('kirim_reas',25)->nullable();
            $table->string('broker_re_reasuradur',25)->nullable();
            $table->string('reasuradur',25)->nullable();
            $table->string('ekawarsa_jangkawarsa',25)->nullable();
            $table->string('tetap_menurun',25)->nullable();
            $table->string('produk',100)->nullable();
            $table->date('tgl_bayar')->nullable();
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
        Schema::dropIfExists('syariah_reinsurance');
    }
}
