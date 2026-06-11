// database/migrations/2026_01_01_000003_modify_users_table.php
return new class extends Migration {
    public function up(): void {
        Schema::table('users', function (Blueprint $table) {
            $table->string('nisn')->nullable()->unique()->after('id');
            $table->enum('role', ['admin', 'guru', 'siswa'])->default('siswa')->after('password');
            $table->enum('gender', ['L', 'P'])->nullable()->after('role');
            $table->foreignId('major_id')->nullable()->constrained('majors')->onDelete('set null')->after('gender');
            $table->foreignId('academic_year_id')->nullable()->constrained('academic_years')->onDelete('set null')->after('major_id');
            $table->enum('status', ['belum_prakerin', 'proses_spk', 'pencocokan', 'lolos_prakerin', 'pembinaan'])
                  ->default('belum_prakerin')->after('academic_year_id');
        });
    }
    public function down(): void {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['nisn', 'role', 'gender', 'major_id', 'academic_year_id', 'status']);
        });
    }
};