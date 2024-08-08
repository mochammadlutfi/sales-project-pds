<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    use HasFactory;
    protected $table = 'branches';
    protected $primary = 'id';
    
    protected $fillable = [
        'name',
        'manager_id',
        'address',
    ];

    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }
}
