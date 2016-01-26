<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFmsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Create table for storing customers
        Schema::create('customers', function (Blueprint $table) {
            $table->increments('id');
            $table->char('name',50)->unique();
            $table->char('eng_name',50)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
        // Create table for storing platforms
        Schema::create('platforms', function (Blueprint $table) {
            $table->increments('id');
            $table->char('name',50)->unique();
            $table->timestamps();
            $table->softDeletes();
        });
        // Create table for storing feeds
        Schema::create('feeds', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('customer_id')->unsigned();
            $table->char('name',100);
            $table->char('location',100);
            $table->enum('content', ['complete', 'modify']);
            $table->enum('type', ['file', 'url']);
            $table->char('description',150);
            $table->timestamps();
            $table->softDeletes();
            
            $table->foreign('customer_id')->references('id')->on('customers')
                ->onUpdate('cascade')->onDelete('cascade');
        });
        // Create table for storing products
        Schema::create('products', function (Blueprint $table) {
            $table->increments('autoid');
            $table->integer('feed_id')->unsigned();
            $table->string('id',100);
            $table->string('name',150);
            $table->string('description',3000);
            $table->string('url',300);
            $table->string('image',300);
            $table->integer('price');
            $table->integer('retail_price');
            $table->char('category',100);
            $table->char('google_category',100);
            $table->char('brand',20);
            $table->char('condition',20);
            $table->char('availability',20);
            $table->timestamps();
            $table->softDeletes();
            
            $table->foreign('feed_id')->references('id')->on('feeds')
                ->onUpdate('cascade')->onDelete('cascade');
            
            $table->unique(array('feed_id', 'id'));
        });
        // Create table for associating platforms to customers (Many-to-Many)
        Schema::create('customer_platform', function (Blueprint $table) {
            $table->integer('customer_id')->unsigned();
            $table->integer('platform_id')->unsigned();
            $table->boolean('active')->default(true);
            $table->foreign('customer_id')->references('id')->on('customers')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('platform_id')->references('id')->on('platforms')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->timestamps();
            $table->primary(['customer_id', 'platform_id']);
        });
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::drop('platforms');
        Schema::drop('feeds');
        Schema::drop('products');
        Schema::drop('customer_platform');
        Schema::drop('customers');
    }
}
