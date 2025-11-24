<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class LeaveRequest extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'leave_type',
        'start_date',
        'end_date',
        'days',
        'reason',
        'file_path',
        'status',
        'approved_by',
        'admin_notes',
        'submitted_at',
        'reviewed_at',
    ];

    protected $casts = [
        'start_date'   => 'date',
        'end_date'     => 'date',
        'submitted_at' => 'datetime',
        'reviewed_at'  => 'datetime',
    ];

    /** Relationships */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /** Scopes */
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

    /** Methods */
    public function calculateDays(): int
    {
        if (!$this->start_date || !$this->end_date) {
            return 0;
        }

        $start = Carbon::parse($this->start_date);
        $end   = Carbon::parse($this->end_date);

        return $start->diffInDays($end) + 1; // inklusif
    }

    public function canBeApproved(): bool
    {
        return $this->status === 'pending';
    }

    public function approve(int $adminId, ?string $notes = null): void
    {
        if (!$this->canBeApproved()) {
            return;
        }

        $this->status      = 'approved';
        $this->approved_by = $adminId;
        $this->admin_notes = $notes;
        $this->reviewed_at = now();
        $this->save();

        $user = $this->user;
        if (!$user) {
            return;
        }

        // Update kuota berdasarkan jenis cuti
        switch ($this->leave_type) {
            case 'tahunan':
                // tracking tahunan untuk dashboard (sisa cuti)
                $user->used_leave_days += $this->days;
                break;

            case 'urusan_penting':
                $user->important_leave_used_days += $this->days;
                break;

            case 'cuti_besar':
                $user->big_leave_used_days += $this->days;
                break;

            case 'cuti_non_aktif':
                $user->non_active_leave_used_days += $this->days;
                break;

            case 'cuti_bersalin':
                $user->maternity_leave_used_count += 1;
                break;

            case 'cuti_sakit':
                $user->sick_leave_used_days += $this->days;
                break;
        }

        $user->save();
    }
    public function reject(int $adminId, ?string $notes = null): void
    {
        if (!$this->canBeApproved()) {
            return;
        }

        $this->status      = 'rejected';
        $this->approved_by = $adminId;
        $this->admin_notes = $notes;
        $this->reviewed_at = now();
        $this->save();
    }
}
