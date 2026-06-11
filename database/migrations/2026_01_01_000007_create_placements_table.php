// database/migrations/2026_01_01_000007_create_placements_table.php
return new class extends Migration {
    public function up(): void {
        Schema::create('placements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            
            // Jika NULL dan status user 'pembinaan', berarti masuk program pembinaan sekolah
            $table->foreignId('company_id')->nullable()->constrained('companies')->onDelete('set null'); 
            
            $table->float('final_smart_score');
            $table->enum('placement_method', ['SYSTEM', 'MANUAL_OVERRIDE'])->default('SYSTEM');
            $table->text('notes')->nullable(); // Catatan jika masuk pembinaan atau kena veto
            $table->foreignId('academic_year_id')->constrained('academic_years')->onDelete('cascade');
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('placements'); }
};