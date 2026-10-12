<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInventoryTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 1. users
        if (!Schema::hasTable('users')) {
            Schema::create('users', function (Blueprint $table) {
                $table->increments('id');
                $table->string('username', 50)->unique();
                $table->string('password_hash', 255);
                $table->enum('role', ['admin', 'staff'])->default('staff');
                $table->dateTime('created_at')->useCurrent();
            });
        }

        // 2. user_profiles (1-to-1)
        if (!Schema::hasTable('user_profiles')) {
            Schema::create('user_profiles', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('user_id')->unsigned()->unique();
                $table->string('full_name', 100);
                $table->string('phone', 25)->nullable();
                $table->text('bio')->nullable();

                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            });
        }

        // 3. categories
        if (!Schema::hasTable('categories')) {
            Schema::create('categories', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name', 100)->unique();
                $table->text('description')->nullable();
                $table->dateTime('created_at')->useCurrent();
            });
        }

        // 4. products (1-to-many from categories)
        if (!Schema::hasTable('products')) {
            Schema::create('products', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('category_id')->unsigned();
                $table->string('sku', 50)->unique();
                $table->string('name', 150);
                $table->decimal('price', 12, 2)->default(0.00);
                $table->integer('stock')->default(0);
                $table->integer('minimum_stock')->default(5);
                $table->timestamps();

                $table->foreign('category_id')->references('id')->on('categories')->onDelete('restrict')->onUpdate('cascade');
                $table->index('category_id');
                $table->index('name');
            });
        }

        // 5. suppliers
        if (!Schema::hasTable('suppliers')) {
            Schema::create('suppliers', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name', 150);
                $table->string('contact_person', 100)->nullable();
                $table->string('phone', 25)->nullable();
                $table->string('email', 100)->nullable();
                $table->text('address')->nullable();
                $table->dateTime('created_at')->useCurrent();
            });
        }

        // 6. product_suppliers (Many-to-Many junction)
        if (!Schema::hasTable('product_suppliers')) {
            Schema::create('product_suppliers', function (Blueprint $table) {
                $table->integer('product_id')->unsigned();
                $table->integer('supplier_id')->unsigned();
                $table->decimal('supplier_price', 12, 2)->default(0.00);

                $table->primary(['product_id', 'supplier_id']);
                $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade')->onUpdate('cascade');
                $table->foreign('supplier_id')->references('id')->on('suppliers')->onDelete('cascade')->onUpdate('cascade');
            });
        }

        // 7. stock_movements
        if (!Schema::hasTable('stock_movements')) {
            Schema::create('stock_movements', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('product_id')->unsigned();
                $table->integer('user_id')->unsigned();
                $table->enum('type', ['IN', 'OUT']);
                $table->integer('quantity');
                $table->string('reference_number', 50)->nullable();
                $table->text('notes')->nullable();
                $table->dateTime('created_at')->useCurrent();

                $table->foreign('product_id')->references('id')->on('products')->onDelete('restrict')->onUpdate('cascade');
                $table->foreign('user_id')->references('id')->on('users')->onDelete('restrict')->onUpdate('cascade');
                $table->index('product_id');
                $table->index('type');
                $table->index('created_at');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stock_movements');
        Schema::dropIfExists('product_suppliers');
        Schema::dropIfExists('suppliers');
        Schema::dropIfExists('products');
        Schema::dropIfExists('categories');
        Schema::dropIfExists('user_profiles');
        Schema::dropIfExists('users');
    }
}
