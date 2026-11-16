<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProposalOutput extends Model
{
    use HasFactory;

    protected $fillable = [
        'proposal_id',
        'jenis_luaran',
        'judul_luaran',
        'nama_jurnal_penerbit',
        'status_capaian',
        'url_bukti',
        'file_bukti',
    ];

    public function proposal(): BelongsTo
    {
        return $this->belongsTo(Proposal::class);
    }
}
