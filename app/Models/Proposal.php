<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Proposal extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_registrasi',
        'grant_scheme_id',
        'user_id',
        'judul',
        'abstrak',
        'kata_kunci',
        'lama_kegiatan_tahun',
        'dana_usulan',
        'dana_disetujui',
        'file_proposal',
        'file_revisi_proposal',
        'nomor_kontrak',
        'status',
        'catatan_admin',
        'submitted_at',
        'decided_at',
    ];

    protected $casts = [
        'dana_usulan' => 'decimal:2',
        'dana_disetujui' => 'decimal:2',
        'submitted_at' => 'datetime',
        'decided_at' => 'datetime',
    ];

    public function scheme(): BelongsTo
    {
        return $this->belongsTo(GrantScheme::class, 'grant_scheme_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function members(): HasMany
    {
        return $this->hasMany(ProposalMember::class);
    }

    public function budgets(): HasMany
    {
        return $this->hasMany(ProposalBudget::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(ProposalReview::class);
    }

    public function logbooks(): HasMany
    {
        return $this->hasMany(ResearchLogbook::class)->orderByDesc('tanggal_kegiatan');
    }

    public function progressReport(): HasOne
    {
        return $this->hasOne(ProgressReport::class)->latestOfMany();
    }

    public function finalReport(): HasOne
    {
        return $this->hasOne(FinalReport::class)->latestOfMany();
    }

    public function outputs(): HasMany
    {
        return $this->hasMany(ProposalOutput::class);
    }

    public function getStatusBadgeAttribute(): array
    {
        return match ($this->status) {
            'draft'              => ['label' => 'Draft Usulan', 'color' => 'secondary'],
            'submitted'          => ['label' => 'Diajukan (Menunggu Verifikasi)', 'color' => 'info'],
            'admin_verified'     => ['label' => 'Lolos Administrasi', 'color' => 'primary'],
            'admin_rejected'     => ['label' => 'Tidak Lolos Administrasi', 'color' => 'danger'],
            'under_review'       => ['label' => 'Sedang Direview Substansi', 'color' => 'warning'],
            'accepted'           => ['label' => 'DITERIMA (Didanai)', 'color' => 'success'],
            'accepted_revision'  => ['label' => 'Diterima (Wajib Revisi Proposal/RAB)', 'color' => 'warning'],
            'rejected'           => ['label' => 'DITOLAK (Tidak Didanai)', 'color' => 'danger'],
            'revision_submitted' => ['label' => 'Revisi Proposal Diunggah', 'color' => 'info'],
            'in_progress'        => ['label' => 'Pelaksanaan Penelitian / Pengabdian', 'color' => 'primary'],
            'progress_submitted' => ['label' => 'Laporan Kemajuan Diunggah', 'color' => 'info'],
            'progress_revision'  => ['label' => 'Revisi Laporan Kemajuan', 'color' => 'warning'],
            'progress_approved'  => ['label' => 'Laporan Kemajuan Disetujui', 'color' => 'success'],
            'final_submitted'    => ['label' => 'Laporan Akhir & Luaran Diunggah', 'color' => 'info'],
            'completed'          => ['label' => 'SELESAI (Tuntas)', 'color' => 'success'],
            default              => ['label' => strtoupper($this->status), 'color' => 'dark'],
        };
    }

    public function averageReviewScore(): float
    {
        $completed = $this->reviews()->where('status_review', 'completed');
        return $completed->count() > 0 ? round((float) $completed->avg('nilai_total'), 2) : 0.0;
    }
}
