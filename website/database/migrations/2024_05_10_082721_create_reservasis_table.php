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
        Schema::create('reservasis', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 20);
            $table->date('tanggal_pemesanan');
            $table->time('jam_awal');
            $table->time('jam_berakhir');
            $table->integer('poin_didapatkan')->default(0);
            $table->integer('poin_digunakan')->default(0);
            $table->double('biaya');
            $table->unsignedBigInteger('id_layanan')->nullable();
            $table->foreign('id_layanan')->references('id')->on('layanans')->nullOnDelete();
            $table->unsignedBigInteger('id_user')->nullable();
            $table->foreign('id_user')->references('id')->on('users')->nullOnDelete();
            $table->unsignedBigInteger('id_karyawan')->nullable();
            $table->foreign('id_karyawan')->references('id')->on('karyawans')->nullOnDelete();
            $table->string('status', 15);
            $table->string('foto_payment')->nullable();
            $table->timestamps();
            $table->timestamp('cancelled_at')->nullable();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservasis');
    }
};
