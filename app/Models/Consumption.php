<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Consumption extends Model
{
    use SoftDeletes;

    protected $guarded = [];
    public static $timSpent = 0 ;

    protected static function booted(): void
    {
        static::saving(function (Consumption $consumption) {

            $consumption_rate = self::calculateConsumption($consumption);
            $consumption->consumption_rate =  $consumption_rate;

                if ($consumption_rate) {
                    # code...
                }
            if ( $consumption_rate> 0  &&  $consumption_rate< 100) {
                $consumption->grade = "A";
            }else
            if ( $consumption_rate > 100 &&  $consumption_rate <= 135) {
                $consumption->grade = "B";

            }else{
                $consumption->grade = "C";
            }


        });
    }

    function personnel() : BelongsTo {
         
        return $this->belongsTo(Personnel::class);


    }
    function batch() : BelongsTo {
         
        return $this->belongsTo(Batch::class);


    }
     protected function status(): Attribute
    {
        return Attribute::make(
            get: function(string $value){
                    if ($value == "2") {
                        return "approved";
                    }
                    if ($value == "4") {
                        return "pending";
                    }
                    if ($value == "3") {
                        return "failed";
                    }
            },
        );
    }
    

     public static function calculateConsumption($record){ 
        $returnTime = Carbon::parse($record->return_time);  
        $departureTime = Carbon::parse($record->departure_time); 
        $difference = $departureTime->diffInMinutes($returnTime);
        self::$timSpent = $difference;
       $result = (($record->departure_pressure - $record->return_pressure) *  $record->cylinder_volume) / $difference;
    
        return round($result);
       }

}
