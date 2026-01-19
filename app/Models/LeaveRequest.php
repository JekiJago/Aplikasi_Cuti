<?php

namespace App\Models;

use App\Models\Holiday;
use App\Models\User;
use App\Models\AnnualQuota;
use App\Models\KuotaTahunan;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class LeaveRequest extends Model
{
    use HasFactory;

    protected $table = 'cuti_pengajuan';

    protected $fillable = [
        'user_id',
        'start_date',
        'end_date',
        'alasan',
        'status',
        'catatan_penolakan',
        'disetujui_oleh',
        'disetujui_pada',
    ];

    protected $casts = [
        'start_date'      => 'date',
        'end_date'        => 'date',
        'disetujui_pada'  => 'datetime',
    ];

    /**
     * Accessor untuk menghitung jumlah hari kerja
     */
    public function getDaysAttribute(): int
    {
        return $this->calculateDays();
    }

    /* ================= RELATIONS ================= */

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'disetujui_oleh');
    }

    /* ================= SCOPES ================= */

    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved(Builder $query): Builder
    {
        return $query->where('status', 'approved');
    }

    public function scopeRejected(Builder $query): Builder
    {
        return $query->where('status', 'rejected');
    }

    public function scopeForYear(Builder $query, int $year): Builder
    {
        return $query->whereYear('start_date', $year)
            ->orWhereYear('end_date', $year);
    }

    /* ================= LOGIC ================= */

    /**
     * Hitung jumlah hari cuti (hari kerja: Senin-Jumat, tanpa libur)
     */
    public function calculateDays(): int
    {
        if (!$this->start_date || !$this->end_date) {
            return 0;
        }

        $start = Carbon::parse($this->start_date);
        $end   = Carbon::parse($this->end_date);

        // Ambil tanggal libur
        $holidayDates = Holiday::pluck('date')
            ->map(fn ($date) => Carbon::parse($date)->format('Y-m-d'))
            ->all();

        $days   = 0;
        $cursor = $start->copy();

        while ($cursor->lte($end)) {
            if (
                !$cursor->isWeekend() &&
                !in_array($cursor->format('Y-m-d'), $holidayDates, true)
            ) {
                $days++;
            }
            $cursor->addDay();
        }

        return $days;
    }

    /**
     * Cek apakah bisa di-approve
     */
    public function canBeApproved(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * ✅ APPROVE CUTI (DAN POTONG KUOTA DENGAN BENAR)
     */
    public function approve(int $adminId, ?string $notes = null): void
    {
        if (!$this->canBeApproved()) {
            throw new \Exception('Cuti tidak bisa disetujui');
        }

        $this->status      = 'approved';
        $this->disetujui_oleh = $adminId;
        $this->catatan_penolakan = $notes;
        $this->disetujui_pada = now();
        
        $this->save();
        // Potong kuota dari tabel kuota_tahunan menggunakan FIFO
        $days = $this->calculateDays();
        if ($days > 0) {
            KuotaTahunan::potonganKuota($this->user_id, $days);
        }    }

    /**
     * REJECT CUTI
     */
    public function reject(int $adminId, ?string $notes = null): void
    {
        if (!$this->canBeApproved()) {
            throw new \Exception('Cuti tidak bisa ditolak');
        }

        $this->status      = 'rejected';
        $this->disetujui_oleh = $adminId;
        $this->catatan_penolakan = $notes;
        $this->disetujui_pada = now();
        $this->save();
    }

    /**
     * 📆 HITUNG HARI KERJA (SENIN–JUMAT, TANPA LIBUR)
     */
    public static function workingDaysBetween(Carbon $start, Carbon $end): int
    {
        if ($start->gt($end)) {
            return 0;
        }

        // Ambil tanggal libur
        $holidayDates = Holiday::pluck('date')
            ->map(fn ($date) => Carbon::parse($date)->format('Y-m-d'))
            ->all();

        $days   = 0;
        $cursor = $start->copy();

        while ($cursor->lte($end)) {
            if (
                !$cursor->isWeekend() &&
                !in_array($cursor->format('Y-m-d'), $holidayDates, true)
            ) {
                $days++;
            }
            $cursor->addDay();
        }

        return $days;
    }

    /**
     * Cek apakah cuti bertabrakan dengan cuti lain yang sudah disetujui
     */
    public static function hasOverlap($userId, $startDate, $endDate, $excludeId = null): bool
    {
        $query = self::where('user_id', $userId)
            ->where('status', 'approved')
            ->where(function ($q) use ($startDate, $endDate) {
                $q->whereBetween('start_date', [$startDate, $endDate])
                  ->orWhereBetween('end_date', [$startDate, $endDate])
                  ->orWhere(function ($q2) use ($startDate, $endDate) {
                      $q2->where('start_date', '<=', $startDate)
                         ->where('end_date', '>=', $endDate);
                  });
            });

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->exists();
    }

    /**
     * Get quota summary for the year of this leave
     */
    public function getYearQuotaSummary(): array
    {
        $year = $this->start_date->year;
        
        $quota = AnnualQuota::where('user_id', $this->user_id)
            ->where('year', $year)
            ->first();

        if (!$quota) {
            return [
                'year' => $year,
                'quota' => 0,
                'used' => 0,
                'remaining' => 0,
                'is_expired' => false
            ];
        }

        return [
            'year' => $year,
            'quota' => $quota->annual_quota,
            'used' => $quota->used_days,
            'remaining' => $quota->remaining_days,
            'is_expired' => $quota->is_expired
        ];
    }
}