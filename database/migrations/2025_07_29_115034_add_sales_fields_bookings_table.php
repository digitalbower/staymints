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
        Schema::table('bookings', function (Blueprint $table) {
            $table->unsignedBigInteger('sales_person_id')->nullable();
            $table->foreign('sales_person_id')->references('id')->on('admins')->onDelete('cascade');
            $table->tinyInteger('status')->default(0); // 0 = Not assigned 1= In Progress, 2= Closed, 3 = Loss
            $table->text('loss_reason')->nullable();
            $table->string('booking_reference')->nullable();
            $table->decimal('total', 10, 2)->nullable();
            $table->decimal('markup_percent', 5, 2)->nullable();
            $table->decimal('markup_value', 10, 2)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropForeign(['sales_person_id']); 
            $table->dropColumn('sales_person_id');
            $table->dropColumn('status');
        });
    }
};
