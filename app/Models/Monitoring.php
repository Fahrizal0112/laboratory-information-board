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
    
    public function isApproved()
    {
        return $this->status === 'approved';
    }
    
    public function isRejected()
    {
        return $this->status === 'rejected';
    }
    
    public function isInProgress()
    {
        return $this->status === 'in_progress';
    }
    
    public function isCompleted()
    {
        return $this->status === 'completed';
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