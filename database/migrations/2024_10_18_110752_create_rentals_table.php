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
        Schema::create('rentals', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('id_unit')->nullable()->constrained('units','id');
            $table->foreignUuid('id_user')->nullable()->constrained('users','user_id');
            $table->foreignUuid('id_customer')->nullable()->constrained('customers','id');
            $table->enum('durasi',['30 Menit','60 Menit','Harian','Mingguan','Bulanan']);
            $table->timestamp('waktu_mulai')->nullable();
            $table->timestamp('waktu_kembali')->nullable();
            $table->decimal('total_biaya');
            $table->string('jaminan');
            $table->enum('status',[1,2]);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rentals');
    }
};
