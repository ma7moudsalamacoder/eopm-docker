<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('fake_cards', function (Blueprint $table) {
            $table->id(); 
            $table->string('holder');
            $table->string('card_number');
            $table->string('type');
            $table->string('cvv');
            $table->string('valid');
            $table->string('status');
            $table->timestamps();
        });
    
        Schema::create('payments', function (Blueprint $table) {
            $table->id(); 
            $table->string('payer_name');
            $table->decimal('amount',10,2)->default(0);
            $table->string('method');
            $table->json('payload')->nullable();
            $table->enum('status', ['pending', 'processing' ,'paid', 'failed'])->default('pending');
            $table->foreignId('order_id')
            ->constrained('orders')
            ->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('fake_cards');
        Schema::dropIfExists('payments');
    }
};
