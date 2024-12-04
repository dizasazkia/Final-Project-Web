<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateStatusEnumInLoansTable extends Migration
{
    public function up()
    {
        Schema::table('loans', function (Blueprint $table) {
            $table->enum('status', ['dipinjam', 'menunggu konfirmasi', 'dikembalikan', 'terlambat'])
                ->default('dipinjam') // Default tetap 'dipinjam'
                ->change();
        });
    }

    public function down()
    {
        Schema::table('loans', function (Blueprint $table) {
            $table->enum('status', ['dipinjam', 'dikembalikan', 'terlambat'])
                ->default('dipinjam') // Mengembalikan ke enum awal
                ->change();
        });
    }
}
