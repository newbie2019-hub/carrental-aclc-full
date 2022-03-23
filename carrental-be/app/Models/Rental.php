<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Rental extends Model
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

    public function car(){
        return $this->belongsTo(Car::class, 'car_id', 'id')->withTrashed();
    }
    
    public function user(){
        return $this->belongsTo(User::class, 'rentee_id', 'id')->withTrashed();
    }
    
    public function rental_info(){
        return $this->belongsTo(RentalInfo::class, 'rental_info_id', 'id')->withTrashed();
    }


}
