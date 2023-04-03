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
        Schema::create('municipio_uf', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('municipio_id')
                ->unsigned();
            $table->bigInteger('uf_id')
                ->unsigned();

            $table->foreign('municipio_id')
                ->references('id')
                ->on('municipios');

            $table->foreign('uf_id')
                ->references('id')
                ->on('ufs');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();

        Schema::table(
            'uf_municipio',
            fn (Blueprint $table) =>
            $table->dropForeign(['municipio_id', 'uf_id'])
        );

        Schema::enableForeignKeyConstraints();

        Schema::dropIfExists('uf_municipio');
    }
};
