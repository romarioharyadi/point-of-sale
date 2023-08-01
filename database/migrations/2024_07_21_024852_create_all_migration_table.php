<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAllMigrationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreign('idRole')->references('idRole')
            ->on('tb_role')->onDelete('cascade')
            ->onUpdate('cascade');
        });

        Schema::table('tb_produk', function (Blueprint $table) {
            $table->foreign('idKategori')->references('idKategori')
            ->on('tb_kategori')->onDelete('cascade')
            ->onUpdate('cascade');
            
            $table->foreign('idUnit')->references('idUnit')
            ->on('tb_unit')->onDelete('cascade')
            ->onUpdate('cascade');
        });

        Schema::table('tb_stok', function (Blueprint $table) {
            $table->foreign('idProduk')->references('idProduk')
            ->on('tb_produk')->onDelete('cascade')
            ->onUpdate('cascade');

            $table->foreign('idSupplier')->references('idSupplier')
            ->on('tb_supplier')->onDelete('cascade')
            ->onUpdate('cascade');

            $table->foreign('idUser')->references('idUser')
            ->on('users')->onDelete('cascade')
            ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('all_migration');
    }
}
