<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Equipment;
use App\Models\EquipmentType;
use App\Http\Resources\EquipmentResource;
use App\Http\Requests\EquipmentStoreRequest;
use Illuminate\Http\Response;

class EquipmentController extends Controller
{

    
    /**
     * Display a listing of the resource.
     * @param App\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Equipment::select();
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
        return EquipmentResource::collection($query->paginate(5));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param App\Http\Requests\EquipmentStoreRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(EquipmentStoreRequest $request)
    {
        $requestData = $request->all();

        $equipmentTypeMask = EquipmentType::find($requestData['equipment_type_id'])->serial_number_mask;
        if(!$this->validateSerialNumber($equipmentTypeMask, $requestData['serial_number'])){
            return response()->json([
                'message' => 'Serial Number ' . $requestData['serial_number'] . ' is incorrect!'], 422);
        }
        
        $createdEquipment = Equipment::create($request->validated());
    
        return new EquipmentResource($createdEquipment);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Equipment $equipment)
    {
        return new EquipmentResource($equipment);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(EquipmentStoreRequest $request, Equipment $equipment)
    {
        $requestData = $request->all();

        $equipmentTypeMask = EquipmentType::find($requestData['equipment_type_id'])->serial_number_mask;
        if(!$this->validateSerialNumber($equipmentTypeMask, $requestData['serial_number'])){
            return response()->json([
                'message' => 'Serial Number ' . $requestData['serial_number'] . ' is incorrect!'], 422);
        }

        $equipment->update($request->validated());

        return new EquipmentResource($equipment);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Equipment $equipment)
    {
        $equipment->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * Validate Serial Number by mask.
     *
     * @param  string  $mask
     * @param  string  $serialNumber
     * @return bool $isValid
     */
    public function validateSerialNumber(string $mask, string $serialNumber): bool {

        $maskToRegExp = [
            'N' => '[0-9]',
            'A' => '[A-Z]',
            'a' => '[a-z]',
            'X' => '[a-z0-9]',
            'Z' => '[-_@]',
        ];
        $pregString = '/';
        $maskArray = str_split($mask);
        foreach($maskArray as $ma){
            if(isset($maskToRegExp[$ma])){
                $pregString .= $maskToRegExp[$ma];
            } else {
                return false;
            }
        }
        $pregString .= '/';
        return boolval(preg_match($pregString, $serialNumber));
    }
}
