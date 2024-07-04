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
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('bidang_id')->change();
            $table->unsignedBigInteger('jabatan_id')->change();
            $table->unsignedBigInteger('hak_akses_id')->change();

            $table->foreign('bidang_id')->references('id')->on('bidangs');
            $table->foreign('jabatan_id')->references('id')->on('jabatans');
            $table->foreign('hak_akses_id')->references('id')->on('hakakses');

            $table->string('default_password')->after('password');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user', function (Blueprint $table) {
            //
        });
    }
};
