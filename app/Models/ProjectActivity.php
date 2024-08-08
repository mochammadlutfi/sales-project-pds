<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectActivity extends Model
{
    use HasFactory;
    protected $table = 'project_activities';

    
    protected $appends = [
        'image_url',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }
    
    public function getImageUrlAttribute($value)
    {
        if($this->attributes['image']){
            return asset($this->attributes['image']);
        }else{
            return asset('/images/placeholders/image.png');
        }
    }
}
