<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Monitoring extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'nama_part',
        'type',
        'no_mol',
        'background',
        'request',
        'part_masuk_lab',
        'start',
        'finish',
        'status',
        'kode_antrian',
        'catatan'
    ];

    protected $casts = [
        'part_masuk_lab' => 'date',
        'start' => 'datetime',
        'finish' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function isPending()
    {
        return $this->status === 'pending';
    }
    
    public function isOnQueue()
    {
        return $this->status === 'on_queue';
    }
    
    public function isRejected()
    {
        return $this->status === 'rejected';
    }
    
    public function isInProgress()
    {
        return $this->status === 'in_progress';
    }
    
    public function isOnProgressApproval()
    {
        return $this->status === 'on_progress_approval';
    }
    
    public function isApprovedFinish()
    {
        return $this->status === 'approved_finish';
    }
    
    // Method untuk mendapatkan label status yang dinamis
    public function getStatusLabelAttribute()
    {
        switch ($this->status) {
            case 'pending':
                return 'Pending';
            case 'on_queue':
                return 'On Queue';
            case 'rejected':
                return 'Rejected';
            case 'in_progress':
                if ($this->request === 'Measuring') {
                    return 'In Progress Measuring';
                } elseif ($this->request === 'Testing') {
                    return 'In Progress Testing';
                }
                return 'In Progress';
            case 'on_progress_approval':
                return 'On Progress Approval';
            case 'approved_finish':
                return 'Approved & Finish';
            default:
                return ucfirst(str_replace('_', ' ', $this->status));
        }
    }
    
    // Legacy methods for backward compatibility
    public function isApproved()
    {
        return $this->status === 'on_queue';
    }
    
    public function isCompleted()
    {
        return $this->status === 'approved_finish';
    }
    
    public function isMeasuring()
    {
        return $this->request === 'Measuring';
    }
    
    public function isTesting()
    {
        return $this->request === 'Testing';
    }
}