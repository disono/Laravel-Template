<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->increments('id');

            $table->string('name', 100)->nullable();
            $table->text('description')->nullable();
            $table->string('sku', 100)->nullable();
            $table->integer('category_id');

            $table->integer('qty')->default(1);
            $table->text('custom_fields')->nullable();

            $table->double('srp', 9, 2)->default(0);
            $table->double('srp_discounted', 9, 2)->default(0);

            $table->boolean('is_taxable')->default(0);
            $table->boolean('is_discountable')->default(0);

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
