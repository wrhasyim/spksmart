namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Company extends Model
{
    protected $fillable = [
        'name', 'address', 'quota', 'major_id', 'gender_requirement',
        'min_total_score', 'min_absensi_score', 'min_fisik_score', 
        'min_keaktifan_score', 'min_administrasi_score', 'academic_year_id'
    ];

    public function major(): BelongsTo
    {
        return $this->belongsTo(Major::class);
    }

    public function academicYear(): BelongsTo
    {
        return $this->belongsTo(AcademicYear::class);
    }
}