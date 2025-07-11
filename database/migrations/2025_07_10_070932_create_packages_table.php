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
        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->string('package_name')->nullable();
            $table->string('slug')->nullable();
            $table->unsignedBigInteger('country_id')->nullable();
            $table->foreign('country_id')->references('id')->on('countries')->onDelete('cascade');
            $table->unsignedBigInteger('category_id')->nullable();
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->unsignedBigInteger('tag_id')->nullable();
            $table->foreign('tag_id')->references('id')->on('tags')->onDelete('cascade');
            $table->unsignedBigInteger('sales_person_id')->nullable();
            $table->foreign('sales_person_id')->references('id')->on('admins')->onDelete('cascade');
            $table->unsignedBigInteger('unit_type_id')->nullable();
            $table->foreign('unit_type_id')->references('id')->on('unit_types')->onDelete('cascade');
            $table->decimal('starting_price', 10, 2)->nullable();
            $table->json('gallery')->nullable();   
            $table->string('image')->nullable();
            $table->string('video')->nullable();
            $table->json('inclusions')->nullable();
            $table->string('duration')->nullable();
            $table->string('group_size')->nullable();
            $table->longText('overview')->nullable();
            $table->longText('highlights')->nullable();
            $table->json('included')->nullable();
            $table->json('excluded')->nullable();
            $table->json('extra_services')->nullable();
            $table->string('meta_title')->nullable();
            $table->string('meta_description')->nullable();
            $table->string('og_title')->nullable();
            $table->string('og_description')->nullable();
            $table->string('og_image')->nullable();
            $table->string('schema')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->tinyInteger('recommendation')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('packages');
    }
};
