<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class GrantScheme extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_skema',
        'nama_skema',
        'kategori',
        'tahun_anggaran',
        'plafon_maksimal',
        'tanggal_buka',
        'tanggal_tutup',
        'is_active',
    ];

    protected $casts = [
        'plafon_maksimal' => 'decimal:2',
        'tanggal_buka' => 'date',
        'tanggal_tutup' => 'date',
        'is_active' => 'boolean',
    ];

    public function proposals(): HasMany
    {
        return $this->hasMany(Proposal::class);
    }

    public function templateBaseName(): string
    {
        return Str::slug($this->kode_skema);
    }

    public function templatePath(): ?string
    {
        foreach (['docx', 'doc'] as $extension) {
            $path = 'scheme-templates/' . $this->templateBaseName() . '.' . $extension;

            if (Storage::disk('local')->exists($path)) {
                return $path;
            }
        }

        return null;
    }

    public function hasTemplate(): bool
    {
        return $this->templatePath() !== null;
    }
}
