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
        Schema::create('images', function (Blueprint $table) {
            $table->id();
            $table->string('name')->default('фото по умолчанию');
            $table->string('path')->default('фото-по-умолчанию.jpg');
            $table->string('type')->default('img');
            // TODO: ALTER TABLE `images` ADD COLUMN `group_code` VARCHAR(255) NULL AFTER `type`
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('images');
    }
};
