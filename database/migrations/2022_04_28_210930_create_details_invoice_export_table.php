<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetailsInvoiceExportTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('details_invoice_export', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_export_id')->constrained('invoice_export');
            $table->foreignId('product_id')->constrained('products');
            $table->integer('quantity');
            $table->integer('into_money');
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
        Schema::dropIfExists('products');
    }
}
