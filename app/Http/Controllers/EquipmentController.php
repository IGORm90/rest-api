<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Equipment;
use App\Models\EquipmentType;
use App\Http\Resources\EquipmentResource;
use App\Http\Resources\MaskResource;
use App\Http\Requests\EquipmentStoreRequest;
use App\Http\Requests\EquipmentUpdateRequest;
use Illuminate\Http\Response;
use App\Service\SearchService;
use App\Service\MaskService;

class EquipmentController extends Controller
{

    /**
     * Define searchService.
     * @param App\Service\SearchService  $searchService
     */
    public function __construct(SearchService $searchService, MaskService $maskService)
    {
        $this->searchService = $searchService;
        $this->maskService = $maskService;
    }
    
    /**
     * Display a listing of the resource.
     * @param App\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Equipment::select();
        
        $queryWithSearch = $this->searchService->makeEquipmentQuery($request,  $query);

        return EquipmentResource::collection($queryWithSearch);
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

        $uncreatedEquipments = [];

        foreach($request->equipments as $equipment){    
            if($this->maskService->validateSerialNumber($equipment['equipment_type_id'], $equipment['serial_number'])){
                $createdEquipment = Equipment::create($equipment);
            } else {
                $uncreatedEquipments[] = $equipment;
            }        
        }

        if($uncreatedEquipments){
            return new MaskResource($uncreatedEquipments);
        }
    
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
     * @param  \Illuminate\Http\EquipmentUpdateRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(EquipmentUpdateRequest $request, Equipment $equipment)
    {
        $requestData = $request->all();

        if($this->maskService->validateSerialNumber($requestData['equipment_type_id'], $requestData['serial_number'])){
            $equipment->update($request->validated());
        }  

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

}
