<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Car extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = [];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i A',
        'updated_at' => 'datetime:Y-m-d h:i A',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d h:i A');
    }

    public function user(){
        return $this->belongsTo(User::class, 'user_id', 'id')->withTrashed();
    }

    public function brand(){
        return $this->belongsTo(CarBrand::class, 'branch_id', 'id')->withTrashed();
    }

    public function branch(){
        return $this->belongsTo(Branch::class, 'branch_id', 'id')->withTrashed();
    }
    
   

}
