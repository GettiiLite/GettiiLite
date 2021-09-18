<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\HelpServices;

class HelpController extends Controller
{
    /** @var HelpServices */
    protected $HelpServices;

    /**
     * HelpController constructor.
     * @param HelpServices $EvenManageServices
     */
    public function __construct(HelpServices $HelpServices)
    {
        $this->HelpServices = $HelpServices;
    }   

    public function index()
    {
        $result = $this->HelpServices->index();
       
        return view('frontend.home.help', ['events' => $result]);
    }
}
