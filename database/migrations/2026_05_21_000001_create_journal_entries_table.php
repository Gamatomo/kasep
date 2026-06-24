<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJournalEntriesTable extends Migration
{
    public function up()
    {
        Schema::create('journal_entries', function (Blueprint $table) {
            $table->increments('id');
            $table->date('date');
            $table->string('reference_number')->nullable();
            $table->text('notes')->nullable();
            $table->unsignedInteger('id_cabang')->nullable();
            $table->unsignedBigInteger('id_user')->nullable();
            $table->timestamps();

            $table->foreign('id_cabang')->references('id')->on('cabang')->onDelete('set null');
            $table->foreign('id_user')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('journal_entries');
    }
}
