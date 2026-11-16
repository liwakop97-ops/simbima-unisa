<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FinalReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'proposal_id',
        'file_laporan_akhir',
        'file_sptb_100',
        'ringkasan_hasil',
        'status_akhir',
        'catatan_akhir',
    ];

    public function proposal(): BelongsTo
    {
        return $this->belongsTo(Proposal::class);
    }
}
