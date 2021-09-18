<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class UserManageTest extends DuskTestCase
{
    /**
     * @group userManageEdit 
     * @return void
     */
    public function testStatusIsCompany(){
        $this->browse(function (Browser $browser) {
            $browser->visit('/userManage/editInf');
            $browser->script('location.reload();');
            $browser->script('userInfEdit.status="company"');
            $browser->assertVisible('@companyInf');
            $browser->assertVisible('@companyContact');
            $browser->assertMissing('@personalInf');
            $browser->assertMissing('@personalContact');
        });
    }
    /**
     * @group userManageEdit 
     * @return void
     */
    public function testStatusIsPersonal(){
        $this->browse(function (Browser $browser) {
            $browser->visit('/userManage/editInf');
            $browser->script('location.reload();');
            $browser->script('userInfEdit.status="personal"');
            $browser->assertVisible('@personalInf');
            $browser->assertVisible('@personalContact');
            $browser->assertMissing('@companyInf');
            $browser->assertMissing('@companyContact');
        });
    }
    /**
     * @group userManageEdit 
     * @return void
     */
    public function testSellTittleHadData(){
        $this->browse(function (Browser $browser) {
            $browser->visit('/userManage/editInf');
            $browser->script('location.reload();');
            $browser->script('userInfEdit.status="company"');
            $browser->script('userInfEdit.sellTittle="測試"');
            $browser->assertMissing('@sellTittle');
        });
    }
    /**
     * @group userManageEdit 
     * @return void
     */
    public function testSellTittleNoneHadData(){
        $this->browse(function (Browser $browser) {
            $browser->visit('/userManage/editInf');
            $browser->script('location.reload();');
            $browser->script('userInfEdit.status="company"');
            $browser->script('userInfEdit.sellTittle=""');
            $browser->script('userInfEdit.$validator.validateAll();');
            $browser->assertVisible('@sellTittle');
        });
    }
    /**
     * @group userManageEdit 
     * @return void
     */
    public function testSellUrlHadData(){
        $this->browse(function (Browser $browser) {
            $browser->visit('/userManage/editInf');
            $browser->script('location.reload();');
            $browser->script('userInfEdit.status="company"');
            $browser->script('userInfEdit.sellUrl="http://www.gettislight.test"');
            $browser->script('userInfEdit.$validator.validateAll();');
            $browser->assertMissing('@sellUrl');
        });
    }
    /**
     * @group userManageEdit 
     * @return void
     */    
    public function testSellUrlNoneHadData(){
        $this->browse(function (Browser $browser) {
            $browser->visit('/userManage/editInf');
            $browser->script('location.reload();');
            $browser->script('userInfEdit.status="company"');
            $browser->script('document.getElementById("sellUrl").value=""');
            $browser->script('userInfEdit.$validator.validateAll();');
            $browser->assertVisible('@sellUrl');
        });
    }
    /**
     * @group userManageEdit 
     * @return void
     */    
    public function testSellUrlFormatIncorrect(){
        $this->browse(function (Browser $browser) {
            $browser->visit('/userManage/editInf');
            $browser->script('location.reload();');
            $browser->script('userInfEdit.status="company"');
            $browser->script('document.getElementById("sellUrl").value="1234"');
            $browser->script('userInfEdit.$validator.validateAll();');
            $browser->assertVisible('@sellUrl');
        });
    }
    /**
     * @group userManageEdit 
     * @return void
     */
    public function testCompanyTitleHadData(){
        $this->browse(function (Browser $browser) {
            $browser->visit('/userManage/editInf');
            $browser->script('location.reload();');
            $browser->script('userInfEdit.status="company"');
            $browser->script('userInfEdit.companyName="測試"');
            $browser->script('userInfEdit.$validator.validateAll();');
            $browser->assertMissing('@companyTitle');
        });
    }
    /**
     * @group userManageEdit 
     * @return void
     */
    public function testCompanyTitleNoneHadData(){
        $this->browse(function (Browser $browser) {
            $browser->visit('/userManage/editInf');
            $browser->script('location.reload();');
            $browser->script('userInfEdit.status="company"');
            $browser->script('userInfEdit.companyName=""');
            $browser->script('userInfEdit.$validator.validateAll();');
            $browser->assertVisible('@companyTitle');
        });
    }
    /**
     * @group userManageEdit 
     * @return void
     */
    public function testCompanyTitleKanaHadData(){
        $this->browse(function (Browser $browser) {
            $browser->visit('/userManage/editInf');
            $browser->script('location.reload();');
            $browser->script('userInfEdit.status="company"');
            $browser->script('userInfEdit.companyNameKana="測試"');
            $browser->script('userInfEdit.$validator.validateAll();');
            $browser->assertMissing('@companyTitleKana');
        });
    }
    /**
     * @group userManageEdit 
     * @return void
     */
    public function testCompanyTitleKanaNoneHadData(){
        $this->browse(function (Browser $browser) {
            $browser->visit('/userManage/editInf');
            $browser->script('location.reload();');
            $browser->script('userInfEdit.status="company"');
            $browser->script('userInfEdit.companyNameKana=""');
            $browser->script('userInfEdit.$validator.validateAll();');
            $browser->assertVisible('@companyTitleKana');
        });
    }
    /**
     * @group userManageEdit 
     * @return void
     */
    public function testContactPersonHadData(){
        $this->browse(function (Browser $browser) {
            $browser->visit('/userManage/editInf');
            $browser->script('location.reload();');
            $browser->script('userInfEdit.status="company"');
            $browser->script('userInfEdit.contactName="測試"');
            $browser->script('userInfEdit.$validator.validateAll();');
            $browser->assertMissing('@contactPerson');
        });
    }
    /**
     * @group userManageEdit 
     * @return void
     */
    public function testContactPersonNoneHadData(){
        $this->browse(function (Browser $browser) {
            $browser->visit('/userManage/editInf');
            $browser->script('location.reload();');
            $browser->script('userInfEdit.status="company"');
            $browser->script('userInfEdit.contactName=""');
            $browser->script('userInfEdit.$validator.validateAll();');
            $browser->assertVisible('@contactPerson');
        });
    }
    /**
     * @group userManageEdit 
     * @return void
     */
    public function testContactTelHadData(){
        $this->browse(function (Browser $browser) {
            $browser->visit('/userManage/editInf');
            $browser->script('location.reload();');
            $browser->script('userInfEdit.status="company"');
            $browser->script('userInfEdit.tel="0171234567"');
            $browser->script('userInfEdit.$validator.validateAll();');
            $browser->assertMissing('@contactTel');
        });
    }
    /**
     * @group userManageEdit 
     * @return void
     */
    public function testContactTelNoneHadData(){
        $this->browse(function (Browser $browser) {
            $browser->visit('/userManage/editInf');
            $browser->script('location.reload();');
            $browser->script('userInfEdit.status="company"');
            $browser->script('userInfEdit.tel=""');
            $browser->script('userInfEdit.$validator.validateAll();');
            $browser->assertVisible('@contactTel');
        });
    }
    /**
     * @group userManageEdit 
     * @return void
     */
    public function testContactTelFormatIncorrect(){
        $this->browse(function (Browser $browser) {
            $browser->visit('/userManage/editInf');
            $browser->script('location.reload();');
            $browser->script('userInfEdit.status="company"');
            $browser->script('userInfEdit.tel="123aaa224"');
            $browser->script('userInfEdit.$validator.validateAll();');
            $browser->assertVisible('@contactTel');
        });
    }
    /**
     * @group userManageEdit 
     * @return void
     */
    public function testContactMailHadData(){
        $this->browse(function (Browser $browser) {
            $browser->visit('/userManage/editInf');
            $browser->script('location.reload();');
            $browser->script('userInfEdit.status="company"');
            $browser->script('userInfEdit.contactMail="test@gmail.com"');
            $browser->script('userInfEdit.$validator.validateAll();');
            $browser->assertMissing('@contactMail');
        });
    }
    /**
     * @group userManageEdit 
     * @return void
     */
    public function testContactMailNoneHadData(){
        $this->browse(function (Browser $browser) {
            $browser->visit('/userManage/editInf');
            $browser->script('location.reload();');
            $browser->script('userInfEdit.status="company"');
            $browser->script('userInfEdit.contactMail=""');
            $browser->script('userInfEdit.$validator.validateAll();');
            $browser->assertVisible('@contactMail');
        });
    }
    /**
     * @group userManageEdit 
     * @return void
     */
    public function testContactMailFormatIncorrect(){
        $this->browse(function (Browser $browser) {
            $browser->visit('/userManage/editInf');
            $browser->script('location.reload();');
            $browser->script('userInfEdit.status="company"');
            $browser->script('document.getElementById("contactMail").value="1234"');
            $browser->script('userInfEdit.$validator.validateAll();');
            $browser->assertVisible('@contactMail');
        });
    }
    /**
     * @group userManageEdit 
     * @return void
     */
    public function testBankNameHadData(){
        $this->browse(function (Browser $browser) {
            $browser->visit('/userManage/editInf');
            $browser->script('location.reload();');
            $browser->script('userInfEdit.status="company"');
            $browser->script('userInfEdit.bankName="測試"');
            $browser->script('userInfEdit.$validator.validateAll();');
            $browser->assertMissing('@bankName');
        });
    }
    /**
     * @group userManageEdit 
     * @return void
     */
    public function testBankNameNoneHadData(){
        $this->browse(function (Browser $browser) {
            $browser->visit('/userManage/editInf');
            $browser->script('location.reload();');
            $browser->script('userInfEdit.status="company"');
            $browser->script('userInfEdit.bankName=""');
            $browser->script('userInfEdit.$validator.validateAll();');
            $browser->assertVisible('@bankName');
        });
    }
    /**
     * @group userManageEdit 
     * @return void
     */
    public function testBankAccountNumHadData(){
        $this->browse(function (Browser $browser) {
            $browser->visit('/userManage/editInf');
            $browser->script('location.reload();');
            $browser->script('userInfEdit.status="company"');
            $browser->script('userInfEdit.bankAccount="12345567"');
            $browser->script('userInfEdit.$validator.validateAll();');
            $browser->assertMissing('@bankAccountNum');
        });
    }
    /**
     * @group userManageEdit 
     * @return void
     */
    public function testBankAccountNumNoneHadData(){
        $this->browse(function (Browser $browser) {
            $browser->visit('/userManage/editInf');
            $browser->script('location.reload();');
            $browser->script('userInfEdit.status="company"');
            $browser->script('userInfEdit.bankAccount=""');
            $browser->script('userInfEdit.$validator.validateAll();');
            $browser->assertVisible('@bankAccountNum');
        });
    }
    /**
     * @group userManageEdit 
     * @return void
     */
    public function testBankLocationHadData(){
        $this->browse(function (Browser $browser) {
            $browser->visit('/userManage/editInf');
            $browser->script('location.reload();');
            $browser->script('userInfEdit.status="company"');
            $browser->script('userInfEdit.branch="測試"');
            $browser->script('userInfEdit.$validator.validateAll();');
            $browser->assertMissing('@bankLocation');
        });
    }
    /**
     * @group userManageEdit 
     * @return void
     */
    public function testBankLocationNoneHadData(){
        $this->browse(function (Browser $browser) {
            $browser->visit('/userManage/editInf');
            $browser->script('location.reload();');
            $browser->script('userInfEdit.status="company"');
            $browser->script('userInfEdit.branch=""');
            $browser->script('userInfEdit.$validator.validateAll();');
            $browser->assertVisible('@bankLocation');
        });
    }
    /**
     * @group userManageEdit 
     * @return void
     */
    public function testBankLocationKanaHadData(){
        $this->browse(function (Browser $browser) {
            $browser->visit('/userManage/editInf');
            $browser->script('location.reload();');
            $browser->script('userInfEdit.status="company"');
            $browser->script('userInfEdit.bankAccountKana="測試"');
            $browser->script('userInfEdit.$validator.validateAll();');
            $browser->assertMissing('@bankAccountKana');
        });
    }
    /**
     * @group userManageEdit 
     * @return void
     */
    public function testBankLocationKanaNoneHadData(){
        $this->browse(function (Browser $browser) {
            $browser->visit('/userManage/editInf');
            $browser->script('location.reload();');
            $browser->script('userInfEdit.status="company"');
            $browser->script('userInfEdit.bankAccountKana=""');
            $browser->script('userInfEdit.$validator.validateAll();');
            $browser->assertVisible('@bankAccountKana');
        });
    }
    /**
     * @group userManageEdit 
     * @return void
     */
    public function testPersonalTitleHadData()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/userManage/editInf');
            $browser->script('location.reload();');
            $browser->script('userInfEdit.status="personal"');
            $browser->script('userInfEdit.personalName="測試"');
            $browser->script('userInfEdit.$validator.validateAll();');
            $browser->assertMissing('@personalTitle');
        });
    }
    /**
     * @group userManageEdit 
     * @return void
     */
    public function testPersonalTitleNoneHadData()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/userManage/editInf');
            $browser->script('location.reload();');
            $browser->script('userInfEdit.status="personal"');
            $browser->script('userInfEdit.personalName=""');
            $browser->script('userInfEdit.$validator.validateAll();');
            $browser->assertVisible('@personalTitle');
        });
    }
    /**
     * @group userManageEdit 
     * @return void
     */
    public function testPersonalContactTelHadData()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/userManage/editInf');
            $browser->script('location.reload();');
            $browser->script('userInfEdit.status="personal"');
            $browser->script('userInfEdit.personalTel="12345678"');
            $browser->script('userInfEdit.$validator.validateAll();');
            $browser->assertMissing('@personalContactTel');
        });
    }
    /**
     * @group userManageEdit 
     * @return void
     */
    public function testPersonalContactTelNoneHadData()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/userManage/editInf');
            $browser->script('location.reload();');
            $browser->script('userInfEdit.status="personal"');
            $browser->script('userInfEdit.personalTel=""');
            $browser->script('userInfEdit.$validator.validateAll();');
            $browser->assertVisible('@personalContactTel');
        });
    }
    /**
     * user manage - click apply,all validator is pass
     * @group userManageEdit 
     * @return void
     */
    public function testAllValidatorPass()
    {
        $this->browse(function (Browser $browser) {
            $data = '{"status":"company","sellTittle":"測試","sellImg":"","sellUrl":"http://www.gettislight.test/userManage/editInf","companyName":"法人","companyNameKana":"nameKana","sellChecked":true,"placeNum":"060","place":"0010","country":"HOKKAIDOSAPPORO SHI CHUO KU","contactDeparment":"who know?","contactName":"contactName","tel":"097919001","contactMail":"test@mail.com","bankName":"whatBank","branch":"loacl","bankType":"normal","bankAccount":"111233334","bankAccountKana":"bakama","personalName":"pername","personalNameKana":"personame","personalTel":"124444333","personalMail":"mn@mail.com","userData":[{"id":0,"name":"測試者壹號","mail":"nnm@gmail.com","permission":"manage","permissionDeadline":"had","deadlineDate":"","infPermission":"Approve","contact":"notApprove","userStatus":"valid","note":""}]}';

            $browser->visit('/userManage/editInf');
            $browser->script("localStorage.setItem('userInf','".$data."')");
            $browser->script('location.reload();');
            $browser->script("userInfEdit.infApply()");
            $browser->pause(1000);
            $browser->assertVisible('@applyComple');
        });
    }
    /**
     * user manage - click apply,all validator is fail
     * @group userManageEdit 
     * @return void
     */
    public function testAllValidatorFail()
    {
        $this->browse(function (Browser $browser) {
            $data = '{"status":"company","sellTittle":"","sellImg":"","sellUrl":"http://www.gettislight.test/userManage/editInf","companyName":"","companyNameKana":"nameKana","sellChecked":true,"placeNum":"12","place":"taibei","contactDeparment":"who know?","contactName":"contactName","tel":"097919001","contactMail":"test@mail.com","bankName":"whatBank","branch":"loacl","bankType":"normal","bankAccount":"111233334","bankAccountKana":"bakama","personalName":"pername","personalNameKana":"personame","personalTel":"124444333","personalMail":"mn@mail.com","userData":[{"id":0,"name":"測試者壹號","mail":"nnm@gmail.com","permission":"manage","permissionDeadline":"had","deadlineDate":"","infPermission":"Approve","contact":"notApprove","userStatus":"valid","note":""}]}';

            $browser->visit('/userManage/editInf');
            $browser->script("localStorage.setItem('userInf','".$data."')");
            $browser->script('location.reload();');
            $browser->script("userInfEdit.infApply()");
            $browser->pause(1000);
            $browser->assertVisible('@validatorFail');
        });
    }
    /**
     * user manage - click dialog btn ,back to indet
     * @group userManageEdit 
     * @return void
     */
    public function testDialogSuccessBtn()
    {
        $this->browse(function (Browser $browser) {
            $data = '{"status":"company","sellTittle":"測試","sellImg":"","sellUrl":"http://www.gettislight.test/userManage/editInf","companyName":"company","companyNameKana":"nameKana","sellChecked":true,"placeNum":"12","place":"taibei","contactDeparment":"who know?","contactName":"contactName","tel":"097919001","contactMail":"test@mail.com","bankName":"whatBank","branch":"loacl","bankType":"normal","bankAccount":"111233334","bankAccountKana":"bakama","personalName":"pername","personalNameKana":"personame","personalTel":"124444333","personalMail":"mn@mail.com","userData":[{"id":0,"name":"測試者壹號","mail":"nnm@gmail.com","permission":"manage","permissionDeadline":"had","deadlineDate":"","infPermission":"Approve","contact":"notApprove","userStatus":"valid","note":""}]}';

            $browser->visit('/userManage/editInf');
            $browser->script("localStorage.setItem('userInf','".$data."')");
            $browser->script('location.reload();');
            $browser->script("userInfEdit.infApply()");
            $browser->pause(1000);
            $browser->script("document.getElementById('successBtn').click()");
            $browser->assertPathIs('/userManage');
        });
    }
    /**
     * user manage - click dialog btn ,hidden dialog
     * @group userManageEdit 
     * @return void
     */
    public function testDialogCloseBtn()
    {
        $this->browse(function (Browser $browser) {
            $data = '{"status":"company","sellTittle":"測試","sellImg":"","sellUrl":"http://www.gettislight.test/userManage/editInf","companyName":"","companyNameKana":"nameKana","sellChecked":true,"placeNum":"12","place":"taibei","contactDeparment":"who know?","contactName":"contactName","tel":"097919001","contactMail":"test@mail.com","bankName":"whatBank","branch":"loacl","bankType":"normal","bankAccount":"111233334","bankAccountKana":"bakama","personalName":"pername","personalNameKana":"personame","personalTel":"124444333","personalMail":"mn@mail.com","userData":[{"id":0,"name":"測試者壹號","mail":"nnm@gmail.com","permission":"manage","permissionDeadline":"had","deadlineDate":"","infPermission":"Approve","contact":"notApprove","userStatus":"valid","note":""}]}';

            $browser->visit('/userManage/editInf');
            $browser->script("localStorage.setItem('userInf','".$data."')");
            $browser->script('location.reload();');
            $browser->script("userInfEdit.infApply()");
            $browser->pause(1000);
            $browser->script("document.getElementById('closeBtn').click()");
            $browser->pause(1000);
            $browser->assertMissing("@dialog");
        });
    }
    /**
     * user manage - back btn
     * @group userManageEdit 
     * @return void
     */
    public function testBackBtn()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/userManage/editInf');
            $browser->script("document.getElementById('backBtn').click()");
            $browser->pause(1000);
            $browser->assertPathIs('/userManage');
        });
    }
    /**
     * user manage - cheak country location data,normal situation
     * @group userManageEdit 
     * @return void
     */
    public function testCountryCheackBtn()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/userManage/editInf');
            $browser->script('userInfEdit.status="company"');
            $browser->script('userInfEdit.placeNum ="060"');
            $browser->script('userInfEdit.place ="0010"');
            $browser->script("document.getElementById('CountryCheack').click()");
            $browser->pause(2000);
            $length = $browser->script("return document.getElementById('countryLocation').innerText.length");
            $length = intval($length);
            $this->assertGreaterThan($length, 15);

        });
    }
    /**
     * user manage - cheak country location data,but country number does not exist
     * @group userManageEdit 
     * @group now
     * @return void
     */
    public function testCountryLocationDoesNotExist()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/userManage/editInf');
            $browser->script('userInfEdit.status="company"');
            $browser->script('userInfEdit.placeNum ="061"');
            $browser->script('userInfEdit.place ="0000"');
            $browser->script("document.getElementById('CountryCheack').click()");
            $browser->pause(1000);
            $browser->assertVisible('@unCountryLocation');
        });
    }
    /**
     * user manage - cheak country location data,but country number does not exist
     * @group userManageEdit 
     * @group now
     * @return void
     */
    public function testCountryNumInputIsNull()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/userManage/editInf');
            $browser->script('userInfEdit.status="company"');
            $browser->script('userInfEdit.placeNum =""');
            $browser->script('userInfEdit.place =""');
            $browser->script("document.getElementById('CountryCheack').click()");
            $browser->pause(1000);
            $browser->assertVisible('@placeNumNull');
            $browser->assertVisible('@placeNull');
        });
    }
    /**
     * user manage - cheak country input value is number
     * @group userManageEdit 
     * @return void
     */
    public function testCountryNumInputIsNumber()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/userManage/editInf');
            $browser->script('userInfEdit.status="company"');
            $browser->script('userInfEdit.placeNum ="123"');
            $browser->assertMissing('@placeNumWarn');
        });
    }
    /**
     * user manage - cheak country input value is number
     * @group userManageEdit 
     * @return void
     */
    public function testCountryNumInputIsNoNumber()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/userManage/editInf');
            $browser->script('userInfEdit.status="company"');
            $browser->script('userInfEdit.placeNum ="aaa"');
            $browser->assertVisible('@placeNumWarn');
        });
    }
    /**
     * user manage - cheak country second input value is number
     * @group userManageEdit 
     * @return void
     */
    public function testCountryNumSecondInputIsNumber()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/userManage/editInf');
            $browser->script('userInfEdit.status="company"');
            $browser->script('userInfEdit.place ="123"');
            $browser->assertMissing('@placeBackNumWarn');
        });
    }
    /**
     * user manage - cheak country second input value is number
     * @group userManageEdit 
     * @return void
     */
    public function testCountryNumSecondInputIsNoNumber()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/userManage/editInf');
            $browser->script('userInfEdit.status="company"');
            $browser->script('userInfEdit.place ="aaa"');
            $browser->assertVisible('@placeBackNumWarn');
        });
    }      
}
