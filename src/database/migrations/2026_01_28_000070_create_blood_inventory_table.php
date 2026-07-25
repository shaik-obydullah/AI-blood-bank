<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('blood_inventory', function (Blueprint $table) {
            $table->id();
            $table->unsignedSmallInteger('fk_blood_group_id');
            $table->unsignedBigInteger('fk_donor_id')->nullable();
            $table->decimal('quantity', 8, 2)->default(0);
            $table->date('collection_date');
            $table->date('expiry_date');
            $table->timestamps();

            $table->foreign('fk_blood_group_id')->references('id')->on('blood_groups')->onDelete('cascade');
            $table->foreign('fk_donor_id')->references('id')->on('donors')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('blood_inventory');
    }
};
