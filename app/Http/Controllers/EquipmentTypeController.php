<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EquipmentType;
use App\Http\Resources\EquipmentTypeResource;
use App\Service\SearchService;

class EquipmentTypeController extends Controller
{

    /**
     * Define searchService.
     * @param App\Service\SearchService  $searchService
     */
    public function __construct(SearchService $searchService)
    {
        $this->searchService = $searchService;
    }

    /**
     * Display a listing of the resource.
     * @param App\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = EquipmentType::select();
        
        $queryWithSearch = $this->searchService->makeEquipmentQuery($request,  $query);

        return EquipmentTypeResource::collection($queryWithSearch);
    }
}
