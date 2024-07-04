<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('surats', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_surat')->nullable();
            $table->date('tanggal_surat');
            $table->string('keterangan_lampiran');
            $table->string('perihal_surat');
            $table->string('alamat_instansi/pejabat');
            $table->string('rincian_pelaksanaan_penugasan');
            $table->string('beban_anggaran');
            $table->unsignedBigInteger('nama_pejabat');
            $table->boolean('e2')->default(0);
            $table->boolean('e3')->default(0);
            $table->boolean('e4')->default(0);
            $table->unsignedBigInteger('pembuat_surat');
            $table->string('pdf');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surats');
    }
};
