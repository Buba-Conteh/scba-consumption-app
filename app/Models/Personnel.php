<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Personnel extends Model
{
    use SoftDeletes;

     protected $guarded = [];


     function consumption() : HasMany {
         
        return $this->hasMany(Consumption::class);
     }

     function batch() : BelongsTo {
        
      return $this->belongsTo(Batch::class);
      }
     function country() : BelongsTo {
        
      return $this->belongsTo(Country::class);
      }

     protected function status(): Attribute
    {
        return Attribute::make(
            get: function(string $value){
                    if ($value == "2") {
                        return "active";
                    }
                    if ($value == "3") {
                        return "graduated";
                    }
                   
            },
        );
    }
     
}
