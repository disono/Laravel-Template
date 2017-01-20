<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('customer_id')->default(0);

            $table->string('ip_address', 100)->nullable();
            $table->string('email', 100)->nullable();
            $table->string('phone', 22)->nullable();

            $table->string('full_name', 100);
            $table->text('billing_address');
            $table->text('shipping_address');

            $table->integer('qty')->default(0);
            $table->double('discount', 9, 2)->default(0);
            $table->double('shipping', 9, 2)->default(0);
            $table->double('tax', 9, 2)->default(0);
            $table->double('total', 9, 2)->default(0);

            $table->integer('payment_type_id');
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
        Schema::dropIfExists('orders');
    }
}
