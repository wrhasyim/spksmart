// database/migrations/2026_01_01_000006_create_assessments_table.php
return new class extends Migration {
    public function up(): void {
        Schema::create('assessments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Referensi ke siswa
            
            // 5 Kriteria Utama (Nilai Mentah 0-100)
            $table->float('absensi');        // Benefit
            $table->float('fisik_mental');   // Benefit
            $table->float('keaktifan');      // Benefit
            $table->float('catatan_kasus');   // Cost (Semakin tinggi semakin buruk)
            $table->float('administrasi');    // Benefit
            
            $table->foreignId('academic_year_id')->constrained('academic_years')->onDelete('cascade');
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('assessments'); }
};