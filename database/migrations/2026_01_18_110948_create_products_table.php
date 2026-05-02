<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Type::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(\App\Models\Tag::class)->nullable()->constrained()->onDelete('set null');
            $table->foreignIdFor(\App\Models\Variant::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(\App\Models\Brand::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(\App\Models\Image::class, 'image_primary_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignIdFor(\App\Models\Image::class, 'image_secondary_id')->nullable()->constrained()->onDelete('set null');
            $table->string('name', 255);
            $table->string('description', 255)->nullable();
            $table->string('video_url', 255)->nullable();
            $table->integer('views')->default(0);
            $table->integer('price');
            $table->tinyInteger('discount')->unsigned()->default(0);
            $table->string('article')->unique();
            $table->boolean('is_stock')->default(true);
            $table->boolean('is_in_store')->default(false);
            $table->timestamps();

            $table->unique(['name', 'variant_id'], 'products_variants_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
