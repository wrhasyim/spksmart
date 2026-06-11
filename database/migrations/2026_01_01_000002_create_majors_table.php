// database/migrations/2026_01_01_000002_create_majors_table.php
return new class extends Migration {
    public function up(): void {
        Schema::create('majors', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // Contoh: "RPL", "TKJ"
            $table->string('name'); // Contoh: "Rekayasa Perangkat Lunak"
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('majors'); }
};