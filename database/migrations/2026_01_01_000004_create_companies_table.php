// database/migrations/2026_01_01_000004_create_companies_table.php
return new class extends Migration {
    public function up(): void {
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('address')->nullable();
            $table->integer('quota');
            
            // Hard Filters
            $table->foreignId('major_id')->constrained('majors')->onDelete('cascade');
            $table->enum('gender_requirement', ['L', 'P', 'ALL'])->default('ALL');
            
            // Passing Grades (Kriteria Khusus / Batas Minimum Nilai SMART)
            $table->integer('min_total_score')->default(0);
            $table->integer('min_absensi_score')->default(0);
            $table->integer('min_fisik_score')->default(0);
            $table->integer('min_keaktifan_score')->default(0);
            $table->integer('min_administrasi_score')->default(0);
            
            $table->foreignId('academic_year_id')->constrained('academic_years')->onDelete('cascade');
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('companies'); }
};