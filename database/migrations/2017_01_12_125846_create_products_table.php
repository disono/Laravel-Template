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

            $table->integer('user_id');
            $table->integer('product_category_id');

            $table->string('name', 100)->nullable();
            $table->text('description')->nullable();
            $table->text('features')->nullable();
            $table->text('custom_fields')->nullable();
            $table->string('sku', 100)->nullable();

            $table->boolean('is_featured')->default(0);
            $table->boolean('is_latest')->default(0);
            $table->boolean('is_sale')->default(0);

            $table->boolean('is_qty_required')->default(1);
            $table->integer('qty')->default(1);

            $table->double('srp', 9, 2)->default(0);
            $table->double('srp_discounted', 9, 2)->default(0);

            $table->boolean('is_taxable')->default(0);
            $table->boolean('is_discountable')->default(0);

            $table->boolean('is_disabled')->default(0);
            $table->boolean('is_draft')->default(0);

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
