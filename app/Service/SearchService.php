<?php

namespace App\Service;

class SearchService
{

    private $paginateNumber = 5;

    /**
     * Display a listing of the resource.
     * @param App\Http\Request  $request
     * @param $searchedQuery
     * @return $query
     */
    public function makeEquipmentQuery($request, $query){

        if($id = $request->input('id')){
            $query->where('id', '=', $id);
        }
        //поиск по серийному номеру/примечанию
        if($t = $request->input('input_text')){
            $query->where('serial_number', 'LIKE', '%'.$t.'%');
            $query->orWhere('note', 'LIKE', '%'.$t.'%');
        }
        if($t = $request->input('equipment_type_id')){
            $query->where('equipment_type_id', '=', $t);
        }
        if($sn = $request->input('serial_number')){
            $query->where('serial_number', 'LIKE', '%'.$sn.'%');
        }
        if($n = $request->input('note')){
            $query->where('note', 'LIKE', '%'.$n.'%');
        }
        return $query->paginate($this->paginateNumber);
        
    }

     /**
     * Display a listing of the resource.
     * @param App\Http\Request  $request
     * @param $searchedQuery
     * @return $query
     */
    public function makeEquipmentTypeQuery($request, $query){

        if($id = $request->input('id')){
            $query->where('id', '=', $id);
        }
        if($n = $request->input('name')){
            $query->where('name', 'LIKE', '%'.$n.'%');
        }
        if($snm = $request->input('serial_number_mask')){
            $query->where('serial_number_mask', 'LIKE', '%'.$snm.'%');
        }
        return $query->paginate($this->paginateNumber);
        
    }

}