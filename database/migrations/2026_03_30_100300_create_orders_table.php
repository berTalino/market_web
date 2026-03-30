<?php

use App\Enums\OrderStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('customer_name');
            $table->string('customer_phone');
            $table->string('customer_email')->nullable();
            $table->text('comment')->nullable();
            $table->string('status')->default(OrderStatus::PENDING->value);
            $table->unsignedBigInteger('subtotal_amount')->default(0);
            $table->unsignedBigInteger('total_amount')->default(0);
            $table->string('session_id')->nullable()->index();
            $table->timestamps();

            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
