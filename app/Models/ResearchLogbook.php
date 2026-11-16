<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ResearchLogbook extends Model
{
    use HasFactory;

    protected $fillable = [
        'proposal_id',
        'tanggal_kegiatan',
        'uraian_kegiatan',
        'persentase_capaian',
        'file_dokumentasi',
    ];

    protected $casts = [
        'tanggal_kegiatan' => 'date',
    ];

    public function proposal(): BelongsTo
    {
        return $this->belongsTo(Proposal::class);
    }
}
