<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Mohsen\Payment\Models\Payment;

class CreatePaymentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('buyer_id');
            $table->foreignId('paymentable_id');
            $table->string('paymentable_type');
            $table->string('amount', 10);
            $table->string('invoice_id');
            $table->string('gateway');
            $table->enum('status', Payment::Statuses);
            $table->tinyInteger('seller_p')->unsigned();
            $table->string('seller_share',10);
            $table->string('site_share',10);

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
        Schema::dropIfExists('payments');
    }
}
