<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Exception;
use Log;
use App\Imports\SeatDataImport;
use Maatwebsite\Excel\Facades\Excel;

class ExcelServices
{
     /** @var  excelData */
     protected $excelData;
     protected $status = true;
     protected $excelFormat = true;
     protected $firstSeat = true;
     protected $dataAttributesNum = 0;
     protected $blockExist = true;
     protected $dataError = '';

    /**
     * handle excel data 
     * 
     * 
     */
     public function excelHandle($filePath){
     
       $filePath = '/home/vagrant/code/gettiilite/'.$filePath;
   
            $a = new SeatDataImport();
            $em = 1;
            $seatSortData = [];
            $directionData = [];
            $seatInfTitle = ['row', 'number', 'floor', 'seatPriority', 'block', 'direction', 'gate'];

           $dataSeat =  Excel::toArray($a,  $filePath);
            foreach($dataSeat as  $reader ){
            foreach ($reader as $key => $item){
                $seatData = $item;
                // dd($seatData);
                foreach($seatData as $keySeat => $value){
                    if($value){
                        // dd($value);
                        $seatCoordinate = $key.'.'.$keySeat;
                        $seatInf = explode("@",$value);
                        if(count($seatInf) != 7)
                        {
                           $this->excelFormat = false;
                        }
                        $seatSortData[$seatCoordinate] = array(
                            'em' => $em,
                            'x' => $keySeat,
                            'y' =>  $key,
                            'row' => ' ',
                            'number' => '',
                            'floor' => '',
                            'seatPriority' => '',
                            'block' => '',
                            'direction' => '',
                            'gate' => '',
                        );
                    
                        if($this->firstSeat){
                            $this->dataAttributesNum = count($seatInf);

                            if(count($seatInf) > 4){
                                if(!is_null($seatInf['4'])){
                                    $this->blockExist = false;
                                }
        
                            }
                            $this->firstSeat = false;
                        }
                       
                        if($this->dataAttributesNum !== count($seatInf)){
                            $this->dataError = 'Attributes had less some';
                            $this->status = false; 
                        }
                     
                        if(count($seatInf) > 4){
                            if(is_null($seatInf['4']) !== $this->blockExist){
                                $this->dataError = 'block data not sama';
                                $this->status = false; 
                            }
                        }

                        if(count($seatInf) > 6){
                            $floorBlock = $seatInf['2'].$seatInf['4'];
                            
                            if(!array_key_exists($floorBlock, $directionData)){
                                $directionData[$floorBlock] = $seatInf['5'];
                            }else{
                                if($directionData[$floorBlock] !== $seatInf['5']){
                                    $this->dataError = 'direction is not same';
                                    $this->status = false; 
                                }
                            }
                        }

                        for($num=0; $num < 7; $num++){
                            $seatInfTitle[$num];
                            if(array_key_exists($num,  $seatInf)){
                                $seatSortData[$key.'.'.$keySeat][$seatInfTitle[$num]] = $seatInf[$num];
                            }
                        }
                      
                    }
                    $em++;
                }
            }
            }
            $this->excelData = $seatSortData;
            $this->status = true; 
     
    }
    /**
     * upload excel file to storage
     * 
     * 
     */
    protected  function excelUpload($file){
        
        if($file){
            $destinationPath = '/public/map-data';
            $fileName = date("dYhmmu");
            $name = $fileName.'.'.$file->getClientOriginalExtension();
            Storage::putFileAs($destinationPath , $file, $name);
        }else{
            $name = null;
        }

        return $fileName;
    }
    /**
     * handle excel data
     * array $inf, Request $request
     */
    public function seatDataHandle(Request $request)
    //public function seatDataHandle()
    {    
        try{
            $fileName = $this->excelUpload($request->file('flieMap'));
            // $fileName = "test";
            $filePath = 'storage/app/public/map-data/'.iconv('UTF-8', 'GBK', $fileName).'.xlsx';
            //$filePath =  'storage/app/public/map-data/'.iconv('UTF-8', 'GBK', 'test').'.xlsx';
        
            $fileNow = 'map-data/'.$fileName.'.xlsx';
            $seatJsonData = [];
            $this->excelHandle($filePath);
            $primary = array(); 
            $errors = array(); 
            $block = array(); 
            $priority = array(); 
            $floorblockCol = array(); 
            $floorblockNum = array(); 
            $floor = null;
            $col = false;
            $number = false;   

            if(!$this->excelFormat){
                         $errors[]= array(
                            'title' => trans('events.S_excelErrMsg_017'),
                            'msn' => '',
                        );                
            }
            
            if($this->status){
                foreach($this->excelData as $key => $value){
                    if(mb_strlen($value['floor']) > 40){
                        $errors[]= array(
                            'title' => trans('events.S_excelErrMsg_001', ['floor' => addslashes($value['floor'])]),
                            'msn' => '',
                        ); 
                    }
                    if(mb_strlen($value['block']) > 40){
                        $errors[]= array(
                            'title' => trans('events.S_excelErrMsg_002', ['block' => addslashes($value['block'])]),
                            'msn' => '',
                        ); 
                    }
                    if(mb_strlen($value['row']) > 20){
                        $errors[]= array(
                            'title' => trans('events.S_excelErrMsg_003', ['row' => addslashes($value['row'])]),
                            'msn' =>  '',
                        ); 
                    }
                    if(mb_strlen($value['number']) > 20){
                        $errors[]= array(
                            'title' => trans('events.S_excelErrMsg_004', ['number' => addslashes($value['number'])]),
                            'msn' =>  '',
                        ); 
                    }
                    if(!empty($value['seatPriority']) && !is_numeric($value['seatPriority'])){
                        $errors[]= array(
                            'title' => trans('events.S_excelErrMsg_005', ['seatPriority' => $value['seatPriority']]),
                            'msn' =>  '',
                        ); 
                    }
                    if($value['seatPriority'] > 32767){
                        $errors[]= array(
                            'title' => trans('events.S_excelErrMsg_006', ['seatPriority' => $value['seatPriority']]),
                            'msn' =>  '',
                        ); 
                    }
                    if(mb_strlen($value['gate']) > 40){
                        $errors[]= array(
                            'title' => trans('events.S_excelErrMsg_007', ['gate' => addslashes($value['gate'])]),
                            'msn' => '',
                        ); 
                    }
                    
                    if($floor === null)
                    {     
                    if(mb_strlen($value['floor']) == 0 )$floor = false;
                    if(mb_strlen($value['floor']) != 0 )$floor = true;
                    if(mb_strlen($value['row']) != 0 )$col = true;
                    if(mb_strlen($value['number']) != 0 )$number = true;
                    }                     

                    //列が設定されている座席が１つでもあれば、全て列は指定しなければいけない
                if($col && mb_strlen($value['row']) == 0){
                    $errors[]= array(
                        'title' => trans('events.S_excelErrMsg_008'),
                        'msn' => '',
                    ); 
                }
                if(!$col && mb_strlen($value['row']) != 0){
                    $errors[]= array(
                        'title' => trans('events.S_excelErrMsg_008'),
                        'msn' => '',
                    ); 
                }
                    
                    //番が設定されている座席が１つでもあれば、全て番は指定しなければいけない
                if($number && mb_strlen($value['number']) == 0){
                    $errors[]= array(
                        'title' => trans('events.S_excelErrMsg_009'),
                        'msn' => '',
                    ); 
                }
                if(!$number && mb_strlen($value['number']) != 0){
                    $errors[]= array(
                        'title' => trans('events.S_excelErrMsg_009'),
                        'msn' => '',
                    ); 
                }
                    

                    //階が設定されている座席が１つでもあれば、全て階は指定しなければいけない
                    if($floor && mb_strlen($value['floor']) == 0){
                        $errors[]= array(
                            'title' => trans('events.S_excelErrMsg_010'),
                            'msn' => '',
                        ); 
                    }
                    if(!$floor && mb_strlen($value['floor']) != 0){
                        $errors[]= array(
                            'title' => trans('events.S_excelErrMsg_010'),
                            'msn' => '',
                        ); 
                    }
                    
                    
                    if(mb_strlen($value['floor']) == 0 )$value['floor'] = trans('events.S_SeatMapFloorDefault'); //階未設定時補完
                    if(mb_strlen($value['block']) == 0 )$value['block'] = trans('events.S_SeatMapBlockDefault'); //ブロック未設定時補完
                    
                    if(!array_key_exists($value['block'], $block))
                    {
                    $block = array_merge($block,array($value['block']=>$value['direction']));    
                    }else{
                    $blockDirectin =  $block[$value['block']];
                    //同一ブロック名では座席の向きは同一でなければいけない
                        if($blockDirectin != $value['direction']){
                            $errors[]= array(
                                'title' => trans('events.S_excelErrMsg_011', ['block' => addslashes($value['block'])]),
                                'msn' => '',
                            ); 
                        }
                    }
                    
                    //seat priority重複check
                    if(!in_array($value['seatPriority'],$priority))
                    {
                    $priority[] = $value['seatPriority'];
                    }else{
                        if(!empty($value['seatPriority'])){
                            $errors[]= array(
                                'title' => trans('events.S_excelErrMsg_012', ['seatPriority' => addslashes($value['seatPriority'])]),
                                'msn' => '',
                            ); 
                        }
                    } 
                    
                    // $direction = '1'; //向きdefault
                    switch ($value['direction']) {
                        case '上':
                        default:
                            $direction = 1;
                            break;
                        case '下':
                            $direction = 2;
                            break;
                        case '左':
                            $direction = 3;
                            break;
                        case '右':
                            $direction = 4;
                            break;
                        // default:
                            // if(!empty($value['direction']))
                            // {    
                            //     $errors[]= array(
                            //         'title' => '013',
                            //         'msn' => trans('events.S_excelErrMsg_013', ['direction' => $value['direction']]),
                            //     ); 
                            // }                
                    }
                //列整合（フロア、ブロックごと）
                if($direction == 2 || $direction == 1) //上 or 下
                {    
                    $coordinate = $value['y'];
                }else{
                    $coordinate = $value['x'];
                }
                if(mb_strlen($value['row']) == 0 ) {
                    $value['row'] = $coordinate + 1; //列未設定時補完               
                }
                if(!array_key_exists($value['floor'].$value['block'].$coordinate, $floorblockCol))$floorblockCol[$value['floor'].$value['block'].$coordinate] = array();             
                array_push($floorblockCol[$value['floor'].$value['block'].$coordinate],$value['row']);
                $floorblockCol[$value['floor'].$value['block'].$coordinate] = array_unique($floorblockCol[$value['floor'].$value['block'].$coordinate]);
                if(count($floorblockCol[$value['floor'].$value['block'].$coordinate]) > 1){
                    $errors[]= array(
                        'title' => trans('events.S_excelErrMsg_014', ['floor' => addslashes($value['floor']),'block' => addslashes($value['block'])]),
                        'msn' => '',
                    ); 
                }
                
                //番整合（フロア、ブロックごと）
                if($direction == 2 || $direction == 1) //上 or 下
                {    
                    $coordinate = $value['x'];
                }else{
                    $coordinate = $value['y'];
                }
                if(mb_strlen($value['number']) == 0 )$value['number'] = $coordinate; //番未設定時補完
                if(!array_key_exists($value['floor'].$value['block'].$coordinate, $floorblockNum))$floorblockNum[$value['floor'].$value['block'].$coordinate] = array();             
                array_push($floorblockNum[$value['floor'].$value['block'].$coordinate],$value['number']);
                $floorblockNum[$value['floor'].$value['block'].$coordinate] = array_unique($floorblockNum[$value['floor'].$value['block'].$coordinate]);
                // if(count($floorblockNum[$value['floor'].$value['block'].$coordinate]) > 1){ 
                //     $errors[]= array(
                //         'title' => '015',
                //         'msn' => trans('events.S_excelErrMsg_015', ['floor' => addslashes($value['floor']),'block' => addslashes($value['block'])]),
                //     ); 
                // }
                foreach($primary as $primaryInfo){
                    if($primaryInfo[0] == $value['floor'] && 
                        $primaryInfo[1] == $value['block'] &&
                        $primaryInfo[2] == $value['row']   &&
                        $primaryInfo[3] == $value['number']){
                        $errors[]= array(
                            'title' => trans('events.S_excelErrMsg_016', ['floor' => addslashes($value['floor']),'block' => addslashes($value['block']),'row' => addslashes($value['row']),'number' => addslashes($value['number'])]),
                            'msn' => '',
                        ); 
                    }
                    }
                    $primary[] = [$value['floor'],$value['block'],$value['row'],$value['number']];
                    if(!array_key_exists($value['floor'], $seatJsonData)){
                        $seatJsonData[$value['floor']] = array(
                            'floorTittle' => $value['floor'],
                            'direction' =>  $direction,
                            'imageUrl' => '',
                            'x_min' => $value['x'],
                            'x_max' => $value['x'],
                            'y_min' => $value['y'],
                            'y_max' => $value['y'],
                            'blockData' => [],
                        );
                    }else{
                        if($seatJsonData[$value['floor']]['x_min'] > $value['x']){
                            $seatJsonData[$value['floor']]['x_min'] = $value['x'];
                        }
                        if($seatJsonData[$value['floor']]['x_max'] < $value['x']){
                            $seatJsonData[$value['floor']]['x_max'] = $value['x'];
                        }
                        if($seatJsonData[$value['floor']]['y_min'] > $value['y']){
                            $seatJsonData[$value['floor']]['y_min'] = $value['y'];
                        }
                        if($seatJsonData[$value['floor']]['y_max'] < $value['y']){
                            $seatJsonData[$value['floor']]['y_max'] = $value['y'];
                        }
                    }
                    
                    if(!array_key_exists($value['block'], $seatJsonData[$value['floor']]["blockData"])){
                        $seatJsonData[$value['floor']]["blockData"][$value['block']] = array(
                            'blockTittle' => $value['block'],
                            'x_min' => $value['x'],
                            'x_max' => $value['x'],
                            'y_min' => $value['y'],
                            'y_max' => $value['y'],
                            'x' => [],
                            'y' => [],
                            'gate' => $value['gate'],
                            'direction' => $direction,
                            'line' => [],
                            'lineNum' => [],
                            'seatData' => [],
                        );
                    
                        $seatJsonData[$value['floor']]["blockData"][$value['block']]['x'][$value['x']] = $value['x'];
                        $seatJsonData[$value['floor']]["blockData"][$value['block']]['y'][$value['y']] = $value['y'];

                        switch ($value['direction']) {
                            case '上':
                            default:
                                $seatJsonData[$value['floor']]["blockData"][$value['block']]['line'][$value['y']] = $value['row'];
                                $seatJsonData[$value['floor']]["blockData"][$value['block']]['lineNum'][$value['x']] = $value['number'];
                                break;
                            case '下':
                                $seatJsonData[$value['floor']]["blockData"][$value['block']]['line'][$value['y']] = $value['row'];
                                $seatJsonData[$value['floor']]["blockData"][$value['block']]['lineNum'][$value['x']] = $value['number'];
                                break;
                            case '左':
                                $seatJsonData[$value['floor']]["blockData"][$value['block']]['line'][$value['x']] = $value['row'];
                                $seatJsonData[$value['floor']]["blockData"][$value['block']]['lineNum'][$value['y']] = $value['number'];
                                break;
                            case '右':
                                $seatJsonData[$value['floor']]["blockData"][$value['block']]['line'][$value['x']] = $value['row'];
                                $seatJsonData[$value['floor']]["blockData"][$value['block']]['lineNum'][$value['y']] = $value['number'];
                                break;
                        }
                    }else{
                        $seatJsonData[$value['floor']]["blockData"][$value['block']]['x'][$value['x']] = $value['x'];
                        $seatJsonData[$value['floor']]["blockData"][$value['block']]['y'][$value['y']] = $value['y'];
                        
                        if($seatJsonData[$value['floor']]["blockData"][$value['block']]['x_min'] > $value['x']){
                            $seatJsonData[$value['floor']]["blockData"][$value['block']]['x_min'] = $value['x'];
                        }
                        if($seatJsonData[$value['floor']]["blockData"][$value['block']]['x_max'] < $value['x']){
                            $seatJsonData[$value['floor']]["blockData"][$value['block']]['x_max'] = $value['x'];
                        }
                        if($seatJsonData[$value['floor']]["blockData"][$value['block']]['y_min'] > $value['y']){
                            $seatJsonData[$value['floor']]["blockData"][$value['block']]['y_min'] = $value['y'];
                        }
                        if($seatJsonData[$value['floor']]["blockData"][$value['block']]['y_max'] < $value['y']){
                            $seatJsonData[$value['floor']]["blockData"][$value['block']]['y_max'] = $value['y'];
                        }
                        if(!in_array($value['row'], $seatJsonData[$value['floor']]["blockData"][$value['block']]['line'])){
                            switch ($value['direction']) {
                                case '上':
                                default:
                                    $seatJsonData[$value['floor']]["blockData"][$value['block']]['line'][$value['y']] = $value['row'];
                                    arsort($seatJsonData[$value['floor']]["blockData"][$value['block']]['line']);
                                    break;
                                case '下':
                                    $seatJsonData[$value['floor']]["blockData"][$value['block']]['line'][$value['y']] = $value['row'];
                                    arsort($seatJsonData[$value['floor']]["blockData"][$value['block']]['line']);
                                    break;
                                case '左':
                                    $seatJsonData[$value['floor']]["blockData"][$value['block']]['line'][$value['x']] = $value['row'];
                                    arsort($seatJsonData[$value['floor']]["blockData"][$value['block']]['line']);
                                    break;
                                case '右':
                                    $seatJsonData[$value['floor']]["blockData"][$value['block']]['line'][$value['x']] = $value['row'];
                                    arsort($seatJsonData[$value['floor']]["blockData"][$value['block']]['line']);
                                    break;
                            }
                        }
                        if(!in_array($value['number'], $seatJsonData[$value['floor']]["blockData"][$value['block']]['lineNum'])){
                            switch ($value['direction']) {
                                case '上':
                                default:
                                    $seatJsonData[$value['floor']]["blockData"][$value['block']]['lineNum'][$value['x']] = $value['number'];
                                    arsort($seatJsonData[$value['floor']]["blockData"][$value['block']]['lineNum']);
                                    break;
                                case '下':
                                    $seatJsonData[$value['floor']]["blockData"][$value['block']]['lineNum'][$value['x']] = $value['number'];
                                    arsort($seatJsonData[$value['floor']]["blockData"][$value['block']]['lineNum']);
                                    break;
                                case '左':
                                    $seatJsonData[$value['floor']]["blockData"][$value['block']]['lineNum'][$value['y']] = $value['number'];
                                    arsort($seatJsonData[$value['floor']]["blockData"][$value['block']]['lineNum']);
                                    break;
                                case '右':
                                    $seatJsonData[$value['floor']]["blockData"][$value['block']]['lineNum'][$value['y']] = $value['number'];
                                    arsort($seatJsonData[$value['floor']]["blockData"][$value['block']]['lineNum']);
                                    break;
                            }
                        }
                    }
                    //優先順位が未設定の項目は、100000*y座標+x座標　を優先順位とする
                    if(!isset($value['seatPriority']))
                    {
                    $value['seatPriority'] = 100000 * $value['y'] + $value['x'];          
                    }
                    
                    switch ($value['direction']) {
                        case '上':
                        case '下':
                        default:
                            $xx = $value['x'];
                            $yy = $value['y'];
                        break;
                        case '左':
                        case '右':
                            $xx = $value['y'];
                            $yy = $value['x'];
                            break;
                    }

                    $seatJsonData[$value['floor']]["blockData"][$value['block']]['seatData'][$key] = array(
                        'x' => $xx,
                        'y' => $yy,
                        'em' => $value['em'],
                        'number' => $value['number'],
                        'rowname' => $value['row'],
                        'seatPriority' => $value['seatPriority'],
                        'typeId' => '',
                        'typeData' => [],
                        'status' =>  'I',
                    );
                }

            }else{
                $seatJsonData = $this->dataError;
            }
         
            if(count($errors) > 0){
                $update_status = false;
                $msn_status = \Config::get('constant.message_status.warning');
                $msn = $errors;
            }else{
                $update_status = true;
                $msn_status = '';
                $msn[] = array(
                    'title' => trans('events.S_map_title_1'),
                    'msn'   => trans('events.S_map_msn_1')
                );
            }

            $update_result = array(
                'status' => array(
                    'update_status' => $update_status,
                    'msn_status' => $msn_status,
                    'title_custom' => true,
                ),
                'data' => array(
                    'title' => trans('events.S_SeatSettingImage'),
                    'msn' =>  $msn,
                )
            );

            $result =  array(
                'status' => $this->status,
                'data' => $seatJsonData,
                'totalSeat' => count($this->excelData),
                'errors' => $errors,
                'update_data' => json_encode($update_result),
            );

            Storage::disk('public')->delete($fileNow);
            // Log::debug('seatDataHandle'.var_export($result,true));
            return $result;
        }catch(Exception $e){
            //\Config::get('constant.message_status.error')
            $msn[] = array(
                'title' => trans('events.S_map_error_title_1'),
                'msn'   => trans('events.S_map_error_msn_1'),
            );

            $update_result = array(
                'status' => array(
                    'update_status' => false,
                    'msn_status' => \Config::get('constant.message_status.error'),
                    'title_custom' => true,
                    'note_custom' => true,
                ),
                'data' => array(
                    'title' => trans('events.S_map_error_title_1'),
                    'msn' =>  $msn,
                    'note' => trans('events.S_map_error_note_1'),
                    'note_sub' => trans('events.S_map_error_note_sub_1'),
                )
            );

            $result =  array(
                'status' => false,
                'data' => '',
                'totalSeat' => 0,
                'errors' => '',
                'update_data' => json_encode($update_result),
            );

            return $result;
        } 
    }
}