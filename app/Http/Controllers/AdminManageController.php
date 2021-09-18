<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AdminManageServices;

use Validator;
use Exception;
use Redirect, Input, Auth, Log;

class AdminManageController extends Controller
{
    /** @var AdminManageServices */
    protected $AdminManageServices;

    /**
     * UserController constructor.
     * @param AdminManageServices $UserManagerServices
     */
    public function __construct(AdminManageServices $AdminManageServices)
    {
        $this->AdminManageServices = $AdminManageServices;
       
    }
    /**
     * update user detail
     * 
     */
    public function detailUpdate(Request $request){
        $json  = json_decode($request['json']);

        $params = [
            'GLID' => $json[0]->data[0]->GLID,
            'SID' => $json[0]->data[0]->SID,
            'freetix' => $json[0]->data[0]->freetix,
            'GETTIIS_disp_flg' => $json[0]->data[0]->GETTIIS_disp_flg,
        ];
        foreach ($json[0]->data[0]->commission_info as $commission) {
            $params['id'][] = property_exists($commission, 'id') ? $commission->id : 0;
            $params['commission_type'][] = $commission->commission_type;
            $params['rate'][] = $commission->rate;
            $params['amount'][] = $commission->amount;
            $params['delete_flg'][] = $commission->delete_flg;
        }
        $Rules = [
            'GLID' => 'integer',
            'SID' => 'integer',
            'freetix' => 'boolean',
            'GETTIIS_disp_flg' => 'integer',
            'id.*' => 'integer',
            'commission_type.*' => 'integer',
            'rate.*' => 'numeric',
            'amount.*' => 'numeric',
            'delete_flg.*' => 'integer',
        ];
        $validator = Validator::make($params, $Rules);

        if($validator->fails()){
            throw new Exception('REQ-ERR-01 Request format error!');
            return null;
        }

        $GLID  = $json[0]->data[0]->GLID;
        $event = $this->AdminManageServices->detailUpdate($json);
       
        return redirect('/dataDetail/'.$GLID)->with(['gssite_update' => $event]);
     }

    /**
     * show user detail 
     * 
     */
    public function detail(Request $request, $GLID){
       $event = $this->AdminManageServices->detail($GLID);

        return view('frontend.adminManage.detail', ['event' => $event]);
    }

    /**
     * show user information
     */
    public function index(Request $request){
        $page = (int)$request->input("page",1);
        $data = $this->AdminManageServices->index($request->all(), $page);

        return view('frontend.adminManage.index', ['data' => $data]);
    }

    /**
     * show user data validation
     * @param user id
     */
    public function dataValidation(Request $request, $GLID){
        $data = $this->AdminManageServices->getValidationData($GLID);
        
        return view('frontend.adminManage.dataValidation', ['data' => $data]);
    }

    /**
     * data validation upload
     * @param user id
     */
    public function dataValidationUpload(Request $request){
        $result = $this->AdminManageServices->dataValidationUpload($request->all());
        return redirect('/adminManage')->with(['applyResult' => json_encode($result)]);
    }

    /**
     * account password change
     * @parm Request $request
     * return $array $result
     */
    public function accountPasswordChange(Request $request){
        $result = $this->AdminManageServices->accountPasswordChange($request->all());
        
        return response()->json(array(
            'status'  => 1,
            'msg'     => $result,
        ));
    
    }

    /**
     * account data change
     * @parm Request $request
     * return $array $result
     */
    public function accountDataUpload(Request $request){
        $GLID = $this->AdminManageServices->accountDataUpload($request->all());
        
        return redirect('/dataValidation/'.$GLID);
    }
}
