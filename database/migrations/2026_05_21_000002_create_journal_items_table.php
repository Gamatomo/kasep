<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJournalItemsTable extends Migration
{
    public function up()
    {
        Schema::create('journal_items', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('journal_entry_id');
            $table->unsignedInteger('account_id');
            $table->unsignedInteger('debit')->default(0);
            $table->unsignedInteger('credit')->default(0);
            $table->text('description')->nullable();
            $table->timestamps();

            $table->foreign('journal_entry_id')->references('id')->on('journal_entries')->onDelete('cascade');
            $table->foreign('account_id')->references('id')->on('chart_of_accounts')->onDelete('restrict');
        });
    }

    public function down()
    {
        Schema::dropIfExists('journal_items');
    }
}
