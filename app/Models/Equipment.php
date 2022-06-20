<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Equipment extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'equipments';

    protected $fillable = [
        'equipment_type_id',
        'serial_number',
        'note',
    ];

    // public function getEquipment($request){
    //     DB::s
    // }

    public function equipmentTypes(){
        return $this->belongsTo(EquipmentType::class);
    }

}
