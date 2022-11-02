<?php

declare(strict_types = 1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// @codingStandardsIgnoreStart
class CreateSignedLinksTable extends Migration
// @codingStandardsIgnoreEnd
{

    public function up(): void
    {
        Schema::create('signed_links', static function (Blueprint $table): void {
            $table->id();
            $table->string('uuid');
            $table->string('route');
            $table->string('method');
            $table->json('route_parameters')->nullable();
            $table->unsignedInteger('used')->default(0);
            $table->unsignedInteger('usage_limit')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->dateTime('available_from');
            $table->dateTime('available_until')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('signed_links');
    }

}
