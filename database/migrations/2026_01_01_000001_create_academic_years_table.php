// database/migrations/2026_01_01_000001_create_academic_years_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('academic_years', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Contoh: "2025/2026 - Ganjil"
            $table->boolean('is_active')->default(false);
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('academic_years'); }
};