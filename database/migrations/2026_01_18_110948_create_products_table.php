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
            $table->foreignIdFor(\App\Models\Tag::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(\App\Models\Variant::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(\App\Models\Brand::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(\App\Models\Image::class, 'image_primary_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignIdFor(\App\Models\Image::class, 'image_secondary_id')->nullable()->constrained()->onDelete('set null');
            $table->string('name');
            $table->string('description');
            $table->string('video_url')->nullable();
            $table->integer('views')->default(0);
            $table->integer('price');
            $table->tinyInteger('discount')->unsigned()->default(0);
            $table->string('article');
            $table->boolean('is_stock')->default(false);
            $table->boolean('is_in_store')->default(false);
            $table->timestamps();
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
