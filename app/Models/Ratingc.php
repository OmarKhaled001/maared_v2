<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ratingc extends Model
{
    use HasFactory ,LogsActivity;

    protected $guarded = [];

    
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults();
    }
    
    public function category(){

        return $this->belongsTo(Category::class); 
        
    }

}
