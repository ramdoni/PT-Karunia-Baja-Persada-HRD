<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMigrationsData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('migration_data', function (Blueprint $table) {
            $table->id();
            $table->string('no_register',100)->nullable();
            $table->string('nomor_invoice',150)->nullable();
            $table->string('nomor_polis',150)->nullable();
            $table->string('nama_pemegang_polis',250)->nullable();
            $table->string('sub_polis',100)->nullable();
            $table->string('sub_pemegang_polis',150)->nullable();
            $table->string('jenis_produk',50)->nullable();
            $table->string('line_of_business',50)->nullable();
            $table->date('tanggal_terbit')->nullable();
            $table->date('jatuh_tempo')->nullable();
            $table->smallInteger('aging')->nullable();
            $table->string('klasifikasi_aging',10)->nullable();
            $table->integer('premi_bruto')->nullable();
            $table->integer('extra_premi')->nullable();
            $table->integer('premi_gross')->nullable();
            $table->integer('discount')->nullable();
            $table->integer('komisi')->nullable();
            $table->integer('fee_base')->nullable();
            $table->integer('lain_lain')->nullable();
            $table->integer('pajak_ppn')->nullable();
            $table->integer('pajak_pph')->nullable();
            $table->integer('pajak_lain')->nullable();
            $table->integer('biaya_administrasi')->nullable();
            $table->integer('biaya_polis')->nullable();
            $table->integer('biaya_sertifikat')->nullable();
            $table->integer('biaya_materai')->nullable();
            $table->integer('premi_netto')->nullable();
            $table->integer('jumlah_bayar')->nullable();
            $table->date('tanggal_pendapatan')->nullable();
            $table->string('no_rekening',150)->nullable();
            $table->string('bank',50)->nullable();
            $table->integer('amount')->nullable();
            $table->integer('pembayaran')->nullable();
            $table->integer('piutang')->nullable();
            $table->string('status',100)->nullable();
            $table->date('pengajuan_komisi')->nullable();
            $table->smallInteger('jumlah_peserta')->nullable();
            $table->string('no_memo',150)->nullable();
            $table->string('period',50)->nullable();
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
        Schema::dropIfExists('migration_data');
    }
}
