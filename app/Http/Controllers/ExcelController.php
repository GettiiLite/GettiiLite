<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ExcelServices;
use Exception;
use Log;

class ExcelController extends Controller
{
    /** @var ExcelServices */
    protected $ExcelServices;

    /**
     * UserController constructor.
     * @param ExcelServices $EvenManageServices
     */
    public function __construct(ExcelServices $ExcelServices)
    {
        $this->ExcelServices = $ExcelServices;
    }    

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {  
      
        $result = $this->ExcelServices->seatDataHandle($request);
        
        return response($result, 201);
    
    }
}
