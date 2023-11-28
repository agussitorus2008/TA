<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePembayaranTable extends Migration
{
    public function up()
    {
        Schema::create('pembayaran', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_user');
            $table->unsignedBigInteger('id_paket');
            $table->decimal('total', 10, 2);
            $table->integer('status');
            $table->timestamps();

            // Kunci asing untuk id_user yang berelasi ke tabel users
            $table->foreign('id_user')->references('id')->on('users')->onDelete('cascade');

            // Kunci asing untuk id_paket yang berelasi ke tabel pakets
            $table->foreign('id_paket')->references('id')->on('pakets')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('pembayaran');
    }
}

