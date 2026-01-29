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
        Schema::create('filter_brands', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Filter::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(\App\Models\Brand::class)->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('filter_brands');
    }
};
