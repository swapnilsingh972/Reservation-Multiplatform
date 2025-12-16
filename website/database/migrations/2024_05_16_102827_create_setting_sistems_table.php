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
        Schema::create('setting_sistems', function (Blueprint $table) {
            $table->id();
            $table->string('logo')->default('logo_default.png');
            $table->string('nama', 20);
            $table->text('alamat');
            $table->string('no_telp', 14);
            $table->time('jam_operasional_buka');
            $table->time('jam_operasional_tutup');
            // $table->integer('jumlah_pembatalan');
            $table->timestamps();
            $table->softDeletes(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('setting_sistems');
    }
};
