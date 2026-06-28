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
        Schema::create('characteristics', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Filter::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(\App\Models\Image::class)->nullable()->constrained()->onDelete('set null');
            $table->string('name', 255);
            $table->string('description', 1000)->nullable(); // TODO: description=1000
//            $table->boolean('is_visible')->default(false);
            $table->timestamps();

            $table->unique(['filter_id', 'name'], 'filter_name_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('characteristics');
    }
};
