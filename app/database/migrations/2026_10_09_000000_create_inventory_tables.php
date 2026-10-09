<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->timestamps();
        });

        Schema::create('warehouses', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('address')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->timestamps();
        });

        Schema::create('barangs', function (Blueprint $table) {
            $table->id();
            $table->string('sku')->unique();
            $table->string('nama');
            $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
            $table->string('satuan');
            $table->decimal('harga_pokok', 14, 2)->default(0);
            $table->decimal('harga_jual', 14, 2)->default(0);
            $table->boolean('aktif')->default(true);
            $table->timestamps();
        });

        Schema::create('inventory_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('number')->unique();
            $table->string('type'); // receipt, sale, transfer
            $table->foreignId('warehouse_id')->nullable()->constrained()->restrictOnDelete();
            $table->foreignId('destination_warehouse_id')->nullable()->constrained('warehouses')->restrictOnDelete();
            $table->foreignId('customer_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('status')->default('posted');
            $table->timestamp('occurred_at');
            $table->timestamp('cancelled_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['type', 'occurred_at']);
        });

        Schema::create('inventory_transaction_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inventory_transaction_id')->constrained()->restrictOnDelete();
            $table->foreignId('barang_id')->constrained()->restrictOnDelete();
            $table->decimal('quantity', 14, 3);
            $table->decimal('unit_price', 14, 2)->default(0);
            $table->timestamps();

            $table->index(['barang_id', 'inventory_transaction_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventory_transaction_items');
        Schema::dropIfExists('inventory_transactions');
        Schema::dropIfExists('barangs');
        Schema::dropIfExists('customers');
        Schema::dropIfExists('warehouses');
        Schema::dropIfExists('categories');
    }
};
