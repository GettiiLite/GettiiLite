<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

function setData(){

    $datetime = date("Y/m/d");
    $weekday  = date('w', strtotime($datetime));
    $weeklist = array('sun', 'mon', 'tue', 'wed', 'thu', 'fri', 'sat');

    $array =array("id"=>"1","title"=>"12:10","date"=>date("Y/n/j"));
    $object = (object) $array;
    $data = array("date"=>date("Y/n/j"),"dateTitle"=>"test1","day"=>$weeklist[$weekday],"hadEvens"=>true,"rule"=>array($object));
    $inf = (object)$data;
    $json = array("date"=>$inf);
    $json = json_encode($json);

    return $json;
}

class EventsInfoTimeCourseTest extends DuskTestCase
{
    
    /**
     * WEB is run
     *
     * 新增規則狀態
     * 
     * @return void
     */
    public function testWeb()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/events/info')
                    ->assertSee('時間，回數設定');
                    $browser->script('$("#timePage").click();');
        });
    }
    
    /**
     *開催期間 測試
     *
     * @return void
     */
    public function testDateInit()
    {
        $this->browse(function (Browser $browser) {

            $date = $browser->value('#showDateRange');
            $date = explode( '-', $date );
            $dateStar = trim($date[0]);
            $dateEnd = trim($date[0]);
            $this->assertEquals(date("Y/m/d"), $dateStar);
            $this->assertEquals(date("Y/m/d"), $dateEnd);
        
        });
    }

    /**
     *特定每日radio選擇測試
     *2018/12/26
     * @return void
     */
    public function testSelectSpecRadios()
    {
        $this->browse(function (Browser $browser) {

            $browser->script('$("#spec-radio").click();');
            $browser->assertRadioSelected('#spec-radio', 'spec')
                    ->assertRadioNotSelected('#nom-radio', 'normal')
                    ->assertMissing('@event-date-setting')
                    ->assertMissing('@event-date-calader');

        });
    }

    /**
     *普通每日radio選擇測試
     *2018/12/26
     * @return void
     */
    public function testSelectNomRadios()
    {
        $this->browse(function (Browser $browser) {

            $browser->script('$("#nom-radio").click();');
            $browser->assertRadioSelected('#nom-radio', 'normal')
                    ->assertRadioNotSelected('#spec-radio', 'spec')
                    ->assertVisible('@event-date-setting')
                    ->assertVisible('@event-date-calader')
                    ->assertMissing('@event-spec-setting');

        });
    }

    /**
     * 一般日子日期設定初始測試
     * 2018/12/26
     * @return void
     */
    public function testNomSettingDateSpec()
    {
        $this->browse(function (Browser $browser) {

            $browser->script('$("#nom-radio").click();');
            $browser->script('$("#date-set-radio-spec").click();');
            $browser->assertRadioSelected('#date-set-radio-spec', 'specDay')
                    ->assertRadioNotSelected('#date-set-radio-nom', 'week')
                    ->assertMissing('@week-sel');

        });
    }

    /**
     * 一般日子日期設定初始測試
     * 2018/12/26
     * @return void
     */
    public function testNomSettingDateNom()
    {
        $this->browse(function (Browser $browser) {

            $browser->script('$("#nom-radio").click();');
            $browser->script('$("#date-set-radio-nom").click();');
            $browser->assertRadioSelected('#date-set-radio-nom', 'week')
                    ->assertRadioNotSelected('#date-set-radio-spec', 'specDay')
                    ->assertMissing('@day-sel')
                    ->assertVisible('@week-sel');

        });
    }

    /**
     * 一般日子編號設定測試
     * init
     * 2018/12/26
     * @return void
     */
    public function testNomSettingRuleNum()
    {
        $this->browse(function (Browser $browser) {

            $browser->script('$("#nom-radio").click();');
            $browser->assertSelectHasOptions('#ruleNum', ['1']);

        });
    }

    /**
     * 一般日子開始時間設定測試
     * 2018/12/26
     * init
     * @return void
     */
    public function testNomSettingTime()
    {
        $this->browse(function (Browser $browser) {

            $browser->script('$("#nom-radio").click();');
            $date = trim($browser->value('#ruleTimePicker'));
            $this->assertEquals('', $date);

        });
    }

    /**
     * 一般日子表示公演日時間測試
     * 2018/12/26
     * init
     * @return void
     */
    public function testNomSettingDateTittle()
    {
        $this->browse(function (Browser $browser) {

            $browser->script('$("#nom-radio").click();');
            $browser->assertInputValue('#even-date-tittle', '');

        });
    }

    /**
     * 規則名稱新增按鈕測試
     *2018/12/28 
     * insert
     * @return void
     */
    public function testAddRuloBtn()
    {
        $this->browse(function (Browser $browser) {
           
            $browser->script('$("#nom-radio").click();');

            $browser->assertVisible('#dateSubTitle')
                    ->click('#dateSubTitle')
                    ->assertSelectHasOptions('#ruleNum', ['1','2'])
                    ->assertVisible('#ruleInput1');

            $json = $browser->script('return localStorage.getItem("ruleData")'); 
            $localStockData = json_decode($json[0]);
            $this->assertEquals('1', $localStockData[0]->id);
        });
    }

    /**
     * 規則追加按鈕測試-特定日
     *2018/12/26
     * 特定日 其他資料是預設
     * @return void
     */
    public function testImportRuloBtn()
    {
        $this->browse(function (Browser $browser) {
           
            $browser->script('$("#nom-radio").click();');
            $browser->script('$("#date-set-radio-spec").click();');
            $browser->script('$("#date-import-btn").click();');

            $browser->assertSelectHasOptions('#ruleNum', ['1','2']);

            $json = $browser->script('return localStorage.getItem("calenderData")'); 
            $localStockData = json_decode($json[0]);
            $ruleData = $localStockData[0]->date->rule[0];
          

            $this->assertEquals(date("Y/n/j"), $localStockData[0]->date->date);
            $this->assertTrue($localStockData[0]->date->hadEvens);
            $this->assertEquals('1', $ruleData->id);
            $this->assertEquals('12：00', $ruleData->title);
            $this->assertEquals(date("Y/n/j"), $ruleData->date);
        });
    }

     /**
     * event - cheack date insert btn is valid
     * @group evantSetting
     * @return void
     */
    public function testImportRuloBtnWeek()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/events/info');
            $browser->script('$("#nom-radio").click();');
            $browser->script('$("#date-set-radio-nom").click();');
            $browser->script('$("#date-set-week-sun").click();');
            $browser->script('timeCourse.starDate="11:22"');
            $browser->script('$("#date-import-btn").click();');

            $browser->assertSelectHasOptions('#ruleNum', ['1','2']);

            $json = $browser->script('return localStorage.getItem("calenderData")'); 
            $localStockData = json_decode($json[0]);
            $ruleData = $localStockData[0]->date->rule[0];
            $dayofweek = date('w', strtotime($localStockData[0]->date->date));
           
            $this->assertEquals('0', $dayofweek);
            $this->assertTrue($localStockData[0]->date->hadEvens);
            $this->assertEquals('1', $ruleData->id);
        });
    }

    /**
     * caleder 新增按鈕測試
     *2019/01/02
     * caleder資料屬於預設
     * @return void
     */
    public function testCalederDataIsHaveInsert()
    {
        $this->browse(function (Browser $browser) {
            
            $json = setData();
            $localStockData = 'localStorage.setItem("calenderData",JSON.stringify(['.$json.']))';
            $browser->script($localStockData);
           
            $browser->assertVisible('#date-import-btn');
            $browser->script('$("#date-import-btn").click();');
          
                   
        });
    }

     /**
     * 一般日子設定 未選則不能按按鈕
     *2019/01/03
     * 資料預設 null
     * @return void
     */
    public function testNoSelectCanClick()
    {
        $this->browse(function (Browser $browser) {
  
            $browser->script('$("#nom-radio").click();');
            $browser->script('$("#date-set-radio-spec").click();');
            
            $browser->assertInputValue('#nomSetDateSpec',  '');

            $btnStatus = $browser->attribute('#date-import-btn', 'disabled');
            $this->assertEquals('true', $btnStatus);
        });
    }

    /**
     * 日子設定選擇測試
     *2019/01/04
     * 資料預設 
     * @return void
     */
    public function testNomDateSetChange()
    {
        $this->browse(function (Browser $browser) {

            $browser->script('$("#nom-radio").click();');
            $json = $browser->script('return localStorage.getItem("timeDataSel")'); 
            $localStockData = json_decode($json[0]);
       
            $sel = $localStockData[0]->type;

            $this->assertEquals('normal', $sel);

        });
    }

     /**
     *特定日子設定選擇測試
     *2019/01/04
     * 資料預設 
     * @return void
     */
    public function testSpeDateSetChange()
    {
        $this->browse(function (Browser $browser) {
            
            $browser->script('$("#spec-radio").click();');
            $json = $browser->script('return localStorage.getItem("timeDataSel")'); 
            $localStockData = json_decode($json[0]);
       
            $sel = $localStockData[0]->type;

            $this->assertEquals('spec', $sel);

        });
    }

    /**
     *特定日子設定測試
     *2019/01/04
     * 資料預設 
     * @return void
     */
    public function testSpeDateSet()
    {
        $this->browse(function (Browser $browser) {
            
            $browser->script('$("#spec-radio").click();');

            $browser->type('@event-spec-even-title', 'testuni1t')
                    ->type('@event-spec-title', 'testunit')
                    ->type('@event-spec-date', '12:00');

            $json = $browser->script('return localStorage.getItem("specDate")'); 
            $localStockData = json_decode($json[0]);
           
            $sel = $localStockData[0]->specTitle;

            $this->assertEquals($sel, 'testunit');
        });
    }
}
