<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ticketSettingTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function testWebIsExist()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/events/info');
            $browser->script('$("#ticketSetPage").click()');
            $browser->assertVisible("#ticketSetting");
        });
    }
    public function testFreeSeatCheack()
    {
        $this->browse(function (Browser $browser) {
            $browser->assertChecked('@freeSeatCheack');
            $browser->assertVisible("@freeSeatContent")
                    ->assertMissing("@selectSeatContent");
        });
    }
    public function testFreeSeatlClearBtn()
    {
        $this->browse(function (Browser $browser) {
            $browser->script('$(".input-del-btn-inside").click()');
            $value = $browser->value('@freeSeatTicketName');
            $this->assertEquals("", $value);
        });
    }
    public function testFreeSeatInsertCol()
    {
        $this->browse(function (Browser $browser) {
            $browser->script('$("#insertFreeSeat").click()');
            $insertData = $browser->script('return $(".free-seat-tittle").length');
            $this->assertEquals(1, $insertData[0]);
        });
    }
    public function testFreeSeatDelCol()
    {
        $this->browse(function (Browser $browser) {
            $browser->script('$("#insertFreeSeat").click()');
            $browser->script('$(".ticket-content-button")[0].click()');
            $insertData = $browser->script('return $(".free-seat-tittle").length');
            $this->assertEquals(1, $insertData[0]);
        });
    }
    public function testFreeSeatColClearBtn()
    {
        $this->browse(function (Browser $browser) {
            $browser->script('$("#insertFreeSeat").click()');
            $browser->script('$(".input-del-btn-inside").click()');
            $value = $browser->value('@freeSeatInput');
            $this->assertEquals("", $value);
        });
    }
    public function testSelectSeatAddBlock()
    {
        $this->browse(function (Browser $browser) {
            $browser->script('ticketSetting.insertSelectSeat()');
            $value = $browser->script('return ticketSetting.settingSeatData.length');
            $this->assertEquals('2', $value[0]);
        });
    }
    public function testSelectSeatDelBlock()
    {
        $this->browse(function (Browser $browser) {
            $browser->script('ticketSetting.removeRowAllSettingSeat(0)');
            $value = $browser->script('return ticketSetting.settingSeatData.length');
            $this->assertEquals('1', $value[0]);
        });
    }
    public function testSelectSeatDelCol()
    {
        $this->browse(function (Browser $browser) {
            $browser->script('ticketSetting.settingSeatData[0].data.push({ticketName: "",ticketPrice: "",ticketEarlyBird: "",ticketNormal: "",ticketOnSite: ""})');
            $browser->script('ticketSetting.removeRowSettingSeat(0, 0)');
            $value = $browser->script('return ticketSetting.settingSeatData[0].data.length');
            $this->assertEquals('0', $value[0]);
        });
    }
    public function testFreeSeatTitleNoInput()
    {
        $this->browse(function (Browser $browser) {
            $browser->script('localStorage.clear();location.reload();');
            $browser->script('$("#insertFreeSeat").click()');
            $browser->script('ticketSetting.inputDataCheack(0,"ticketWarn0",0, "")');
            $value = $browser->script('return document.getElementById("ticketWarn0").style.display');
            $this->assertEquals('none', $value[0]);
        });
    }
    public function testFreeSeatTitleNoInputMoreCol()
    {
        $this->browse(function (Browser $browser) {
            $browser->script('location.reload();');
            $browser->script('$("#insertFreeSeat").click();$("#insertFreeSeat").click();');
            $browser->script('ticketSetting.inputDataCheack(0,"ticketWarn0",0, "")');
            $value = $browser->script('return document.getElementById("ticketWarn0").style.display');
            $this->assertEquals('block', $value[0]);
        });
    }
    public function testSelectSeatCheack()
    {
        $this->browse(function (Browser $browser) {
            $browser->script('location.reload();');
            $browser->script('$("#selectSeatCheack").click()');
            $browser->pause(1000);
            $browser->assertVisible("@selectSeatContent")
                    ->assertMissing("@freeSeatContent");
        });
    }
    public function testSelectSeatTitleNoInput()
    {
        $this->browse(function (Browser $browser) {
            $browser->script('location.reload();');
            $browser->script('$("#selectSeatCheack").click()');
            $browser->script('$(".insertSelectSeat")[0].click()');
            $browser->script('ticketSetting.inputSelectSeatDataCheack(0,"ticketWarn00","selectSeatBlock0", 0,"")');
            $value = $browser->script('return document.getElementById("ticketWarn00").style.display');
            $this->assertEquals('none', $value[0]);
        });
    }
    public function testWarnHad()
    {
        $this->browse(function (Browser $browser) {
            $browser->script('localStorage.clear();location.reload();');
            $browser->script('document.getElementById("insertFreeSeat").click()');
            $browser->script('document.getElementById("insertFreeSeat").click()');
            $browser->script('ticketSetting.inputDataCheack(0,"ticketWarn0",0, "");ticketSetWarn(freeSeat")');

            $warn = $browser->script('return document.getElementById("updateBtn").disabled');
            $this->assertTrue($warn[0]);
            $browser->assertVisible("#ticketSetWarn");
        });
    }
    public function testPriceInTitleSame()
    {
        $this->browse(function (Browser $browser) {
            $browser->script('location.reload();');
            $browser->script('document.getElementById("insertFreeSeat").click()');
            $browser->script('document.getElementById("insertFreeSeat").click()');
            $browser->script('ticketSetting.inputDataCheack(0,"ticketWarn0",0, "")');
            $browser->script('let freeTicketAll = document.getElementById("freeTicket");let parentCol = freeTicketAll.getElementsByClassName("free-seat-tittle");let printCol = freeTicketAll.getElementsByClassName("free-ticket-price");parentCol[0].value="test";parentCol[1].value="test";printCol[0].value="123";printCol[1].value="123";');
            $browser->script('ticketSetting.inputPriceCheack(1)');
            
            $warn = $browser->script('return document.getElementsByClassName("free-ticket-price-warn")[1].style.display');

            $this->assertEquals('block', $warn[0]);
        });
    }
    public function testPriceDiffInTitleSame()
    {
        $this->browse(function (Browser $browser) {
            $browser->script('location.reload();');
            $browser->script('document.getElementById("insertFreeSeat").click()');
            $browser->script('document.getElementById("insertFreeSeat").click()');
            $browser->script('ticketSetting.inputDataCheack(0,"ticketWarn0",0, "")');
            $browser->script('let freeTicketAll = document.getElementById("freeTicket");let parentCol = freeTicketAll.getElementsByClassName("free-seat-tittle");let printCol = freeTicketAll.getElementsByClassName("free-ticket-price");parentCol[0].value="test";parentCol[1].value="test";printCol[0].value="123";printCol[1].value="1231";');
            $browser->script('ticketSetting.inputPriceCheack(1)');
            
            $warn = $browser->script('return document.getElementsByClassName("free-ticket-price-warn")[1].style.display');

            $this->assertEquals('none', $warn[0]);
        });
    }
    public function testTicketFreeSettingHadData()
    {
        $this->browse(function(Browser $browser){
            $data = '{"settingType":"freeSeat","seatQty":0,"data":{"data":[{"ticketName":"","ticketPrice":"","ticketEarlyBird":"","ticketNormal":"","ticketOnSite":""},{"ticketName":"","ticketPrice":"","ticketEarlyBird":"","ticketNormal":"","ticketOnSite":""},{"ticketName":"","ticketPrice":"","ticketEarlyBird":"","ticketNormal":"","ticketOnSite":""}],"seat":""}}';
            $json = "localStorage.setItem('ticketSetting','".$data."')";

            $browser->script('location.reload();');
            $browser->script($json);
            $browser->script('window.location.reload();');
            $browser->pause(1000);
            $browser->assertVisible("@freeSeatInput");
        });
    }
    public function testTicketAllSettingHadData()
    {
        $this->browse(function(Browser $browser){
            $data = '{"settingType":"selectSeat","seatQty":0,"data":[{"seatName":"test","data":[{"ticketName":"","ticketPrice":"","ticketEarlyBird":"","ticketNormal":"","ticketOnSite":""},{"ticketName":"","ticketPrice":"","ticketEarlyBird":"","ticketNormal":"","ticketOnSite":""}]},{"seatName":"test","data":[{"ticketName":"","ticketPrice":"","ticketEarlyBird":"","ticketNormal":"","ticketOnSite":""},{"ticketName":"","ticketPrice":"","ticketEarlyBird":"","ticketNormal":"","ticketOnSite":""}]}]}';
            $json = "localStorage.setItem('ticketSetting','".$data."')";

            $browser->script('location.reload();');
            $browser->script($json);
            $browser->script('window.location.reload();');
            $browser->pause(1000);
            $browser->assertVisible("#setInpt00")
                    ->assertMissing("@freeSeatContent");
        });
    }
}

