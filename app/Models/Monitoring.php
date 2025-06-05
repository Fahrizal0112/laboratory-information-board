<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Monitoring extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nama_part',
        'type',
        'no_mol',
        'background',
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
}