<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EquipmentType;
use App\Http\Resources\EquipmentTypeResource;

class EquipmentTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     * @param App\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = EquipmentType::select();
        if($id = $request->input('id')){
            $query->where('id', '=', $id);
        }
        if($n = $request->input('name')){
            $query->where('name', 'LIKE', '%'.$n.'%');
        }
        if($snm = $request->input('serial_number_mask')){
            $query->where('serial_number_mask', 'LIKE', '%'.$snm.'%');
        }
        return EquipmentTypeResource::collection($query->paginate(5));
    }
}
