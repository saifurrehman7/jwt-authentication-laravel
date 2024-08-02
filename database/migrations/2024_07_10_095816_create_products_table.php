<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('all_products', function (Blueprint $table) {
            $table->id();
            $table->string('product_name')->default('null');
            $table->longText('product_title')->default('null');
            $table->string('product_price')->default('null');
            $table->string('added_by')->default('null'); 
            $table->timestamps();
        });
        DB::table('all_products')->insert([
            ['product_name' => 'Samsung LED', 'product_title' => '"Experience the Future of Display Technology with Samsung LED Solutions','product_price' => '49.99', 'added_by' => 'SuperAdmin'],
        ]); 
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('all_products');
    }
}
