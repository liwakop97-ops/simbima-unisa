<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProposalReview extends Model
{
    use HasFactory;

    protected $fillable = [
        'proposal_id',
        'reviewer_id',
        'skor_rumusan_masalah',
        'skor_tinjauan_pustaka',
        'skor_metode',
        'skor_luaran',
        'skor_rab',
        'nilai_total',
        'rekomendasi',
        'komentar_reviewer',
        'status_review',
        'reviewed_at',
    ];

    protected $casts = [
        'nilai_total' => 'decimal:2',
        'reviewed_at' => 'datetime',
    ];

    public function proposal(): BelongsTo
    {
        return $this->belongsTo(Proposal::class);
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }

    /**
     * Hitung nilai terbobot sesuai standar penilaian substansi BIMA (Skala 1-100)
     */
    public static function hitungNilaiTerbobot(
        int $rumusan,
        int $pustaka,
        int $metode,
        int $luaran,
        int $rab
    ): float {
        return round(
            ($rumusan * 0.20) +
            ($pustaka * 0.15) +
            ($metode * 0.25) +
            ($luaran * 0.25) +
            ($rab * 0.15),
            2
        );
    }
}
