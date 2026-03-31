<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pedido_detalles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pedido_id')->constrained()->cascadeOnDelete();
            $table->string('producto');
            $table->integer('cantidad');
            $table->decimal('precio', 10, 2);
            $table->decimal('subtotal', 10, 2);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pedido_detalles');
    }
};
