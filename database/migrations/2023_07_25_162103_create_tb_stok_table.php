<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbStokTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_stok', function (Blueprint $table) {
            $table->increments('idStok');
            $table->integer('idProduk')->unsigned();
            $table->integer('idSupplier')->unsigned();
            $table->integer('idUser')->unsigned();
            $table->enum('tipe', ['masuk','keluar']);
            $table->text('detail');
            $table->integer('qty');
            $table->date('date');
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
        Schema::dropIfExists('tb_stok');
    }
}
