// database/migrations/2026_01_01_000005_create_student_preferences_table.php
return new class extends Migration {
    public function up(): void {
        Schema::create('student_preferences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Harus role 'siswa'
            $table->foreignId('company_option_1')->constrained('companies')->onDelete('cascade');
            $table->foreignId('company_option_2')->nullable()->constrained('companies')->onDelete('set null');
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('student_preferences'); }
};