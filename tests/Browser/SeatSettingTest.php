<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class SeatSettingTest extends DuskTestCase
{

    public function testSeatSettingCorrect()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/events/info');
            $browser->script('localStorage.setItem("seatMapData",JSON.stringify([{"totalSeat":78,"unSet":78,"dataSet":[{"id":"0","title":"S","icon":"","color":"#16A085","total":0},{"id":"1","title":"A","icon":"自由","color":"#9B59B6","total":0}]}]));');
            $browser->assertSee('S席')
                    ->value('#seatTotal', '78')
                    ->value('#seatUnsetTotal', '30');
        });
    }

    public function testSeatSettingOptionCorrect()
    {
        $this->browse(function (Browser $browser) {
            $browser->assertSelectHasOptions('#SeatSettingOption', ['0', '1']);
        });
    }

    public function testSeatSettingBtnDisabled()
    {
        $this->browse(function (Browser $browser) {
            $btnStatus = $browser->attribute('@seatSettingBtn', 'disabled');
            $this->assertEquals('true', $btnStatus);
        });
    }

    public function testSeatClearBtnDisabled()
    {
        $this->browse(function (Browser $browser) {
            $btnStatus = $browser->attribute('@seatClearBtn', 'disabled');
            $this->assertEquals('true', $btnStatus);
        });
    }

    public function testVenueUpload()
    {
        $this->browse(function (Browser $browser) {
            $browser->attach('#imgInp', __DIR__.'/image/venuaTest.gif');
            $json = $browser->script('return localStorage.getItem("venuaImage")'); 
            $localStockData = json_decode($json[0]);
            $this->assertRegExp('/data/', $localStockData);
        });
    }
    
    public function testMapInit()
    {
        $this->browse(function (Browser $browser) {
            $array = [];
            $line = [];
            for($n=1; $n<=5; $n++){
                for($m=1; $m<=20; $m++){
                    $line[$m] = $m;
                    $array[$n.'.'.$m] = array("x"=>$n,"y"=>$m,"sid"=>$n+$m,"sale"=>true,"vacant"=>true);
                }
            }
            $datamap = array("x_min"=>1,"x_max"=>5,"y_min"=>1,"y_max"=>20,"seats"=>(object)$array,"lines"=>(object)$line);
            $data = array("bid"=>"77835b6c250df1fa4b65f3fa0d384c045808f45f96c117ec1a71c6d1a421812f","direction"=>1,"stock"=>50,"map"=>(object)$datamap);
            $map = array("info"=>(object)$data,"statusCode"=>"200");
            $seatMap = json_encode($map);
            $seatMap = json_decode($seatMap);
            $mapData = 'vueApp.map='.json_encode($seatMap->info->map);
            $browser->script($mapData); 

            $browser->script('vueApp.drawMapUp()');
            $mapTd = $browser->script('return $(".cliseat").length');

            $this->assertLessThan($mapTd[0], 0);
        });
    }

    public function testMapInitDontHadData()
    {
        $this->browse(function (Browser $browser) {

            $browser->script('vueApp.map = ""'); 
            $browser->script('vueApp.drawMapUp()');
            $mapTd = $browser->script('return $(".cliseat").length');

            $this->assertLessThanOrEqual(0, $mapTd[0]);
        });
    }

    public function testVenueAreaInit()
    {
        $this->browse(function (Browser $browser) {
            
            $venueAreaData = 'vueApp.venueAreaName=[14,25,14,25,23,12,9,17,24,12]';
            $browser->script($venueAreaData); 

            $mapTd = $browser->script('return $(".venue-area").length');
            
            $this->assertLessThan($mapTd[0], 0);
        });
    }

    public function testVenueAreaDontHadData()
    {
        $this->browse(function (Browser $browser) {
            
            $venueAreaData = 'vueApp.venueAreaName=""';
            $browser->script($venueAreaData); 

            $mapTd = $browser->script('return $(".venue-area").length');
            
            $this->assertLessThanOrEqual(0, $mapTd[0]);
        });
    }
}
