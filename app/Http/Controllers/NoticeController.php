<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\NoticeServices;

class NoticeController extends Controller
{
    /** @var NoticeServices */
    protected $NoticeServices;

    /**
     * NoticeController constructor.
     * @param NoticeServices $EvenManageServices
     */
    public function __construct(NoticeServices $NoticeServices)
    {
        $this->NoticeServices = $NoticeServices;
    }   

    public function index()
    {
        $result = $this->NoticeServices->index();
       
        return view('frontend.home.notice', ['events' => $result]);
    }
}
