<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Struktur Tabel Barang
        Schema::create('barang', function (Blueprint $table) {
            $table->string('id_barang', 8)->primary();
            $table->string('nama', 50);
            $table->integer('harga');
            
            $table->timestamp('timestamp')->default(DB::raw('CURRENT_TIMESTAMP'));
            
            $table->timestamps();
        });

        // Function untuk Generate ID Otomatis
        DB::unprepared('
            CREATE OR REPLACE FUNCTION func_generate_id_barang()
            RETURNS TRIGGER AS $$
            DECLARE
                nr_count INTEGER;
            BEGIN
                -- Menghitung jumlah record yang dibuat pada hari ini
                SELECT count(*) INTO nr_count 
                FROM barang 
                WHERE CAST("timestamp" AS DATE) = CURRENT_DATE;

                -- Format ID: TahunBulanHari + Nomor Urut (lpad memastikan 2 digit, misal: 01, 02)
                -- Contoh: 26030101
                NEW.id_barang := to_char(CURRENT_DATE, \'YYMMDD\') || lpad((nr_count + 1)::text, 2, \'0\');
                
                RETURN NEW;
            END;
            $$ LANGUAGE plpgsql;
        ');

        // Trigger sebelum Insert
        DB::unprepared('
            CREATE TRIGGER trigger_id_barang
            BEFORE INSERT ON barang
            FOR EACH ROW
            EXECUTE FUNCTION func_generate_id_barang();
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Menghapus Trigger, Function, dan Tabel saat Rollback
        DB::unprepared('DROP TRIGGER IF EXISTS trigger_id_barang ON barang');
        DB::unprepared('DROP FUNCTION IF EXISTS func_generate_id_barang()');
        Schema::dropIfExists('barang');
    }
};