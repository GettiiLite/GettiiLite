<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class UserInfIndexTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function testStatusIsCompany()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/userManage');
            $browser->script('location.reload();');
            $browser->script('userManage.status="company"');
            $browser->assertVisible('@companyInf');
            $browser->assertVisible('@companyContact');
            $browser->assertMissing('@personalInf');
            $browser->assertMissing('@personalContact');
        });
    }
    public function testStatusIsPersonal()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/userManage');
            $browser->script('location.reload();');
            $browser->script('userManage.status="personal"');
            $browser->assertVisible('@personalInf');
            $browser->assertVisible('@personalContact');
            $browser->assertMissing('@companyInf');
            $browser->assertMissing('@companyContact');
        });
    }
    public function testCompanySellTittleViewOnly()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/userManage');
            $browser->script('location.reload();');
            $browser->script('userManage.status="company"');
            $attribute = $browser->attribute('#sellTittle', 'readonly')? true: false;
            $this->assertTrue($attribute);
        });
    }
    public function testCompanyDeparmentViewOnly()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/userManage');
            $browser->script('location.reload();');
            $browser->script('userManage.status="company"');
            $attribute = $browser->attribute('#contactDeparment', 'readonly')? true: false;
            $this->assertTrue($attribute);
        });
    }
    public function testCompanyBankAccountNumViewOnly()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/userManage');
            $browser->script('location.reload();');
            $browser->script('userManage.status="company"');
            $attribute = $browser->attribute('#bankAccountNum', 'readonly')? true: false;
            $this->assertTrue($attribute);
        });
    }
    public function testPersonalNameViewOnly()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/userManage');
            $browser->script('location.reload();');
            $browser->script('userManage.status="personal"');
            $attribute = $browser->attribute('#personalName', 'readonly')? true: false;
            $this->assertTrue($attribute);
        });
    }
    public function testPersonalContactTelViewOnly()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/userManage');
            $browser->script('location.reload();');
            $browser->script('userManage.status="personal"');
            $attribute = $browser->attribute('#personalTel', 'readonly')? true: false;
            $this->assertTrue($attribute);
        });
    }
    public function testGetLocalStockData()
    {
        $this->browse(function (Browser $browser) {
            $data = '{"status":"company","sellTittle":"測試","sellImg":"","sellUrl":"","companyName":"法人","companyNameKana":"","placeNum":"","place":"","contactDeparment":"","contactName":"","tel":"","contactMail":"","bankName":"","branch":"","bankType":"","bankAccount":"","bankAccountKana":"","personalName":"","personalNameKana":"","personalTel":"","personalMail":"","userData":""}';
            $json = "localStorage.setItem('ticketSetting','".$data."')";
            $browser->script('location.reload();');
            $browser->pause(1000);
            $browser->assertInputValue("#companyName", "法人");
        });
    }
    public function testUserAddBtn()
    {
        $this->browse(function (Browser $browser) {
            $browser->script('location.reload();');
            $browser->click('@addUserBtn');
            $browser->assertVisible("@userSettingDialog");
        });
    }
    /**
     * Dialog test - Dialog user name is null
     * @group userManage 
     * @return void
     */
    public function testAddUserDialogUserNameIsNull()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/userManage');
            $browser->script('location.reload();');
            $browser->script('userManage.showModal=true');
            $browser->script('userManage.name=""');
            $browser->script('userManage.$validator.validateAll();');
            $browser->assertVisible('@userNameWarn');
        });
    }
    /**
     * Dialog test - Dialog user name had data
     * @group userManage 
     * @return void
     */
    public function testAddUserDialogUserNameHadData()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/userManage');
            $browser->script('location.reload();');
            $browser->script('userManage.showModal=true');
            $browser->script('userManage.name="isNotNull"');
            $browser->script('userManage.$validator.validateAll();');
            $browser->assertMissing('@userNameWarn');
        });
    }
    /**
     * Dialog test - Dialog validate is failed,btn can'not click
     * @group userManage 
     * @return void
     */
    public function testAddUserDialogValidateFailed()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/userManage');
            $browser->script('location.reload();');
            $browser->script('userManage.showModal=true');
            $browser->script('userManage.name=""');
            $browser->script('userManage.addUserData()');
            $browser->assertVisible('@userSettingDialog');
        });
    }
    /**
     * Dialog test - Dialog validate is pass,btn can click
     * @group userManage 
     * @return void
     */
    public function testAddUserDialogValidatePass()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/userManage');
            $browser->script('location.reload();');
            $browser->script('userManage.openDialog()');
            $browser->script('userManage.name="測試"');
            $browser->script('userManage.addUserData()');
            $browser->assertMissing('@userSettingDialog');
        });
    }
    /**
     * Dialog test - Dialog add data
     * @group userManage 
     * @return void
     */
    public function testDialogAddData()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/userManage');
            $browser->script('location.reload();');
            $browser->script('userManage.openDialog()');
            $browser->script('userManage.name="測試者壹號"');
            $browser->script('userManage.addUserData()');
            $browser->pause(1000);
            $browser->assertVisible('@col-0');
            $browser->assertSeeIn('@user-0', '測試者壹號');
        });
    }
    /**
     * Dialog test - click user inf change button,show data 
     * @group userManage 
     * @return void
     */
    public function testClickChangeUserInfBtn()
    {
        $this->browse(function (Browser $browser) {
            $data = '{"status":"company","sellTittle":"測試","sellImg":"","sellUrl":"","companyName":"法人","companyNameKana":"","placeNum":"","place":"","contactDeparment":"","contactName":"","tel":"","contactMail":"","bankName":"","branch":"","bankType":"","bankAccount":"","bankAccountKana":"","personalName":"","personalNameKana":"","personalTel":"","personalMail":"","userData":[{"id":0,"name":"測試者壹號","mail":"nnm@gmail.com","permission":"manage","permissionDeadline":"had","deadlineDate":"","infPermission":"Approve","contact":"notApprove","userStatus":"valid","note":""}]}';

            $browser->visit('/userManage');
            $browser->script("localStorage.setItem('userInf','".$data."')");
            $browser->script('location.reload();');
            $browser->pause(1000);
            $browser->script('document.getElementById("changeUserData0").click()');
            $browser->assertVisible('@userSettingDialog')
                    ->assertInputValue('@userName', '測試者壹號')
                    ->assertInputValue('@dialog-mail', 'nnm@gmail.com')
                    ->assertChecked('@permission-deadline')
                    ->assertChecked('@inf-permission')
                    ->assertChecked('@content-not-approve')
                    ->assertSelected('@user-status', 'valid');
        });
    }
    /**
     * Dialog test - save inf change data
     * @group userManage 
     * @return void
     */
    public function testChangeUserInf()
    {
        $this->browse(function (Browser $browser) {
            $data = '{"status":"company","sellTittle":"測試","sellImg":"","sellUrl":"","companyName":"法人","companyNameKana":"","placeNum":"","place":"","contactDeparment":"","contactName":"","tel":"","contactMail":"","bankName":"","branch":"","bankType":"","bankAccount":"","bankAccountKana":"","personalName":"","personalNameKana":"","personalTel":"","personalMail":"","userData":[{"id":0,"name":"測試者壹號","mail":"nnm@gmail.com","permission":"manage","permissionDeadline":"had","deadlineDate":"","infPermission":"Approve","contact":"notApprove","userStatus":"valid","note":""}]}';

            $browser->visit('/userManage');
            $browser->script("localStorage.setItem('userInf','".$data."')");
            $browser->script('location.reload();');
            $browser->pause(1000);
            $browser->script('document.getElementById("changeUserData0").click()');
            $browser->script('userManage.name="測試改"');
            $m = $browser->value('@userName');
            $browser->script('document.getElementById("dialogAcceptBtn").click()');
            $browser->pause(1000);
            $browser->assertSeeIn('@user-0', '測試改');
        });
    }
    /**
     * Dialog test - user password change Dialog is valid
     * @group userManage 
     * @return void
     */
    public function testPasswordDialogValid()
    {
        $this->browse(function (Browser $browser) {
            $data = '{"status":"company","sellTittle":"測試","sellImg":"","sellUrl":"","companyName":"法人","companyNameKana":"","placeNum":"","place":"","contactDeparment":"","contactName":"","tel":"","contactMail":"","bankName":"","branch":"","bankType":"","bankAccount":"","bankAccountKana":"","personalName":"","personalNameKana":"","personalTel":"","personalMail":"","userData":[{"id":0,"name":"測試者壹號","mail":"nnm@gmail.com","permission":"manage","permissionDeadline":"had","deadlineDate":"","infPermission":"Approve","contact":"notApprove","userStatus":"valid","note":""}]}';

            $browser->visit('/userManage');
            $browser->script("localStorage.setItem('userInf','".$data."')");
            $browser->script('location.reload();');
            $browser->pause(1000);
            $browser->script('document.getElementById("changeUserPassword0").click()');
            $browser->assertVisible('@changPassWordDialog');
        });
    }
    /**
     * Dialog test - user password change Dialog data is correct
     * @group userManage 
     * @return void
     */
    public function testPasswordDialogDataCorrect()
    {
        $this->browse(function (Browser $browser) {
            $data = '{"status":"company","sellTittle":"測試","sellImg":"","sellUrl":"","companyName":"法人","companyNameKana":"","placeNum":"","place":"","contactDeparment":"","contactName":"","tel":"","contactMail":"","bankName":"","branch":"","bankType":"","bankAccount":"","bankAccountKana":"","personalName":"","personalNameKana":"","personalTel":"","personalMail":"","userData":[{"id":0,"name":"測試者壹號","mail":"nnm@gmail.com","permission":"manage","permissionDeadline":"had","deadlineDate":"","infPermission":"Approve","contact":"notApprove","userStatus":"valid","note":""}]}';

            $browser->visit('/userManage');
            $browser->script("localStorage.setItem('userInf','".$data."')");
            $browser->script('location.reload();');
            $browser->pause(1000);
            $browser->script('document.getElementById("changeUserPassword0").click()');
            $browser->assertVisible('@changPassWordDialog');
            $browser->assertSeeIn('@userChangePassword', '測試者壹號');
        });
    }
    /**
     * Dialog test - password change Dialog,select show in web
     * @group userManage 
     * @return void
     */
    public function testPasswordDialogSelectWebsite()
    {
        $this->browse(function (Browser $browser) {
            $data = '{"status":"company","sellTittle":"測試","sellImg":"","sellUrl":"","companyName":"法人","companyNameKana":"","placeNum":"","place":"","contactDeparment":"","contactName":"","tel":"","contactMail":"","bankName":"","branch":"","bankType":"","bankAccount":"","bankAccountKana":"","personalName":"","personalNameKana":"","personalTel":"","personalMail":"","userData":[{"id":0,"name":"測試者壹號","mail":"nnm@gmail.com","permission":"manage","permissionDeadline":"had","deadlineDate":"","infPermission":"Approve","contact":"notApprove","userStatus":"valid","note":""}]}';

            $browser->visit('/userManage');
            $browser->script("localStorage.setItem('userInf','".$data."')");
            $browser->script('location.reload();');
            $browser->pause(1000);
            $browser->script('document.getElementById("changeUserPassword0").click()');
            $browser->script('userManage.passwordSelect="web"');
            $browser->script('document.getElementById("PasswordSend").click()');
            $browser->assertVisible('@PassWordDialogShowWeb');
        });
    }
    /**
     * Dialog test - password change Dialog,select send mail  
     * @group userManage 
     * @return void
     */
    public function testPasswordDialogSelectMail()
    {
        $this->browse(function (Browser $browser) {
            $data = '{"status":"company","sellTittle":"測試","sellImg":"","sellUrl":"","companyName":"法人","companyNameKana":"","placeNum":"","place":"","contactDeparment":"","contactName":"","tel":"","contactMail":"","bankName":"","branch":"","bankType":"","bankAccount":"","bankAccountKana":"","personalName":"","personalNameKana":"","personalTel":"","personalMail":"","userData":[{"id":0,"name":"測試者壹號","mail":"nnm@gmail.com","permission":"manage","permissionDeadline":"had","deadlineDate":"","infPermission":"Approve","contact":"notApprove","userStatus":"valid","note":""}]}';

            $browser->visit('/userManage');
            $browser->script("localStorage.setItem('userInf','".$data."')");
            $browser->script('location.reload();');
            $browser->pause(1000);
            $browser->script('document.getElementById("changeUserPassword0").click()');
            $browser->script('userManage.passwordSelect="mail"');
            $browser->script('document.getElementById("PasswordSend").click()');
            $browser->assertVisible('@PassWordDialogMail');
        });
    }
    /**
     * Dialog test - init data,cheack account id  
     * @group userManage 
     * @return void
     */
    public function testPasswordDialogUserAccountl()
    {
        $this->browse(function (Browser $browser) {
            $data = '{"account":"userId","status":"company","sellTittle":"測試","sellImg":"","sellUrl":"","companyName":"法人","companyNameKana":"","placeNum":"","place":"","contactDeparment":"","contactName":"","tel":"","contactMail":"","bankName":"","branch":"","bankType":"","bankAccount":"","bankAccountKana":"","personalName":"","personalNameKana":"","personalTel":"","personalMail":"","userData":[{"id":0,"name":"測試者壹號","mail":"nnm@gmail.com","permission":"manage","permissionDeadline":"had","deadlineDate":"","infPermission":"Approve","contact":"notApprove","userStatus":"valid","note":""}]}';

            $browser->visit('/userManage');
            $browser->script("localStorage.setItem('userInf','".$data."')");
            $browser->script('location.reload();');
            $browser->pause(1000);
            $browser->assertSeeIn('@userAccount', 'userId');
        });
    }
    /**
     * user manage - edit inf btn
     * @group userManage 
     * @return void
     */
    public function testBackBtn()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/userManage');
            $browser->script("document.getElementById('efitInfBtn').click()");
            $browser->pause(1000);
            $browser->assertPathIs('/userManage/editInf');
        });
    }
}
