<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoiceExportTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoice_export', function (Blueprint $table) {
            $table->id();
            $table->string('code_invoice')->unique();
            $table->integer('into_money')->default(0);
            $table->foreignId('user_id')->constrained('users')->nullable();
            $table->foreignId('admin_id')->constrained('users')->nullable();
            $table->string('status_ship')->default(null);
            $table->boolean('is_pay_cod')->default(false);
            $table->boolean('is_payment')->default(false);
            $table->text('message')->nullable();
            $table->string('email_user');
            $table->string('phone_user');
            $table->string('name_user');
            $table->string('address');
            $table->integer('need_pay')->default(0);
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
