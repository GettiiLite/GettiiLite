<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class LoginTest extends DuskTestCase
{
    /**
     * login page - cheack input of company id in empty , show warn 
     * @group LoginTest
     * @return void
     */
    public function testCompanyIdEmpty(){
        $this->browse(function (Browser $browser) {
            $browser->visit('/login');
            $browser->script('loginPage.$validator.validate("company-id")');
            $browser->assertVisible('@companyId');
        });
    }  
    /**
     * login page - cheack input of company id in empty , warn display none
     * @group LoginTest
     * @return void
     */
    public function testCompanyIdNoEmpty(){
        $this->browse(function (Browser $browser) {
            $browser->visit('/login');
            $browser->script('loginPage.$validator.validate("company-id")');
            $browser->script('loginPage.companyId = "c0001"');
            $browser->assertMissing('@companyId');
        });
    }
    /**
     * login page - cheack input of user id in empty , show warn 
     * @group LoginTest
     * @return void
     */
    public function testUserIdEmpty(){
        $this->browse(function (Browser $browser) {
            $browser->visit('/login');
            $browser->script('loginPage.$validator.validate("user-id")');
            $browser->assertVisible('@userId');
        });
    }  
    /**
     * login page - cheack input of user id in empty , warn display none
     * @group LoginTest
     * @return void
     */
    public function testUserIdNoEmpty(){
        $this->browse(function (Browser $browser) {
            $browser->visit('/login');
            $browser->script('loginPage.$validator.validate("user-id")');
            $browser->script('loginPage.userId = "c0001"');
            $browser->assertMissing('@userId');
        });
    }
    /**
     * login page - cheack input of user password in empty , show warn 
     * @group LoginTest
     * @return void
     */
    public function testUserPasswordEmpty(){
        $this->browse(function (Browser $browser) {
            $browser->visit('/login');
            $browser->script('loginPage.$validator.validate("user-password")');
            $browser->assertVisible('@userPassword');
        });
    }  
    /**
     * login page - cheack input of user password in empty , warn display none
     * @group LoginTest
     * @return void
     */
    public function testUserPasswordNoEmpty(){
        $this->browse(function (Browser $browser) {
            $browser->visit('/login');
            $browser->script('loginPage.$validator.validate("user-password")');
            $browser->script('loginPage.userPassword = "1234"');
            $browser->assertMissing('@userPassword');
        });
    }

    /**
     * login page - apply button , cheack redirect is correct
     * @group LoginTest
     * @return void
     */
    public function testButtonApply(){
        $this->browse(function (Browser $browser) {
            $browser->visit('/login');
            $browser->script('document.getElementById("buttonApply").click()');
            $browser->pause(1000);
            $browser->assertPathBeginsWith('/register');
        });
    }  
    /**
     * login page -  forget password button , cheack redirect is correct
     * @group LoginTest
     * @return void
     */
    public function testForgetPasswordButton(){
        $this->browse(function (Browser $browser) {
            $browser->visit('/login');
            $browser->script('document.getElementById("buttonLosePassword").click()');
            $browser->pause(1000);
            $browser->assertPathBeginsWith('/accountReminder');
        });
    }
    /**
     * registered - apply button, personal data is correct
     * @group registeredTest
     * @return void
     */
    public function testApplyPersonCorrect()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login');
            $browser->script('loginPage.companyId = "c00001"');
            $browser->script('loginPage.userId = "u00001"');
            $browser->script('loginPage.userPassword = "1234"');
            $browser->script('document.getElementById("buttonApply").click()');
            $browser->pause(1000);
            $browser->assertPathBeginsWith('/register/complete');
        });
    }
    /**
     * account Reminder - cheack input of company id  in empty , show warn 
     * @group AccountReminderTest
     * @return void
     */
    public function testReminderIdEmpty(){
        $this->browse(function (Browser $browser) {
            $browser->visit('/accountReminder');
            $browser->script('accountReminder.$validator.validate("company-id")');
            $browser->assertVisible('@companyId');
        });
    }  
    /**
     * account Reminder - cheack input of company id in empty , warn display none
     * @group AccountReminderTest
     * @return void
     */
    public function testReminderIdNoEmpty(){
        $this->browse(function (Browser $browser) {
            $browser->visit('/accountReminder');
            $browser->script('accountReminder.$validator.validate("company-id")');
            $browser->script('accountReminder.companyId = "c0001"');
            $browser->assertMissing('@companyId');
        });
    }
    /**
     * account Reminder - cheack input of user mail in empty , show warn 
     * @group AccountReminderTest
     * @return void
     */
    public function testUserMailEmpty(){
        $this->browse(function (Browser $browser) {
            $browser->visit('/accountReminder');
            $browser->script('accountReminder.$validator.validate("user-mail")');
            $browser->assertVisible('@userMail');
        });
    }  
    /**
     * account Reminder - cheack input of company id in empty , warn display none
     * @group AccountReminderTest
     * @return void
     */
    public function testUserMailNoEmpty(){
        $this->browse(function (Browser $browser) {
            $browser->visit('/accountReminder');
            $browser->script('accountReminder.$validator.validate("user-mail")');
            $browser->script('accountReminder.mail = "c0001@gmail.com"');
            $browser->assertMissing('@userMail');
        });
    }
    /**
     * account Reminder - cheack input of company mail formal fail , warn display
     * @group AccountReminderTest
     * @return void
     */
    public function testUserMailFormal(){
        $this->browse(function (Browser $browser) {
            $browser->visit('/accountReminder');
            $browser->script('accountReminder.$validator.validate("user-mail")');
            $browser->script('accountReminder.mail = "c0001aa"');
            $browser->assertVisible('@userMail');
        });
    }
    /**
     * account Reminder - cheack input of company id  in empty , show warn 
     * @group AccountReminderTest
     * @return void
     */
    public function testUserTelEmpty(){
        $this->browse(function (Browser $browser) {
            $browser->visit('/accountReminder');
            $browser->script('accountReminder.$validator.validate("user-tel")');
            $browser->assertVisible('@userTel');
        });
    }  
    /**
     * account Reminder - cheack input of company id in empty , warn display none
     * @group AccountReminderTest
     * @return void
     */
    public function testUserTelNoEmpty(){
        $this->browse(function (Browser $browser) {
            $browser->visit('/accountReminder');
            $browser->script('accountReminder.$validator.validate("user-tel")');
            $browser->script('accountReminder.tel = "1111110001"');
            $browser->assertMissing('@userTel');
        });
    }
    /**
     * account Reminder - cheack input of company id is not number , warn show warn 
     * @group AccountReminderTest
     * @return void
     */
    public function testUserTelFormal(){
        $this->browse(function (Browser $browser) {
            $browser->visit('/accountReminder');
            $browser->script('accountReminder.$validator.validate("user-tel")');
            $browser->script('accountReminder.tel = "c0ws11001"');
            $browser->assertVisible('@userTel');
        });
    }
    /**
     * account Reminder - apply button, apply data is correct
     * @group AccountReminderTest
     * @group nowTest
     * @return void
     */
    public function testForgetPasswordCorrect()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/accountReminder');
            $browser->script('accountReminder.companyId = "c00001"');
            $browser->script('accountReminder.mail = "u00001@mail.com"');
            $browser->script('accountReminder.tel = "123456789"');
            $browser->script('document.getElementById("buttonApply").click()');
            $browser->pause(1000);
            $browser->assertPathBeginsWith('/');
        });
    }
    /**
     * account Reminder - apply button, apply data is uncorrect
     * @group AccountReminderTest
     * @return void
     */
    public function testForgetPasswordUncorrect()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/accountReminder');
            $browser->script('accountReminder.companyId = "c00001"');
            $browser->script('accountReminder.mail = "u000"');
            $browser->script('accountReminder.tel = "123456789"');
            $browser->script('document.getElementById("buttonApply").click()');
            $browser->pause(1000);
            $browser->assertPathBeginsWith('/accountReminder');
        });
    }
    /**
     * password dialog - old pass is null
     * @group PasswordDialogTest
     * @return void
     */
    public function testOldPasswordNull()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/userManage');
            $browser->script('adminSetting.showModal = true');
            $browser->script('adminSetting.oldPassword = ""');
            $browser->script('adminSetting.$validator.validate("old-password")');
            $browser->assertVisible('@oldPassword');
        });
    }
    /**
     * password dialog - old pass had
     * @group PasswordDialogTest
     * @return void
     */
    public function testOldPasswordHad()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/userManage');
            $browser->script('adminSetting.showModal = true');
            $browser->script('adminSetting.$validator.validate("old-password")');
            $browser->script('adminSetting.oldPassword = "12345"');
            $browser->assertMissing('@oldPassword');
        });
    }
    /**
     * password dialog - new pass is null
     * @group PasswordDialogTest
     * @return void
     */
    public function testNewPasswordNull()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/userManage');
            $browser->script('adminSetting.showModal = true');
            $browser->script('adminSetting.newPassword = ""');
            $browser->script('adminSetting.$validator.validate("new-password")');
            $browser->assertVisible('@newPassword');
        });
    }
    /** 
    * password dialog - new pass had
    * @group PasswordDialogTest
    * @return void
    */
   public function testNewPasswordHad()
   {
       $this->browse(function (Browser $browser) {
           $browser->visit('/userManage');
           $browser->script('adminSetting.showModal = true');
           $browser->script('adminSetting.$validator.validate("new-password")');
           $browser->script('adminSetting.newPassword = "1234567"');
           $browser->assertMissing('@newPassword');
       });
   }
    /**
     * password dialog - new se pass is null
     * @group PasswordDialogTest
     * @return void
     */
    public function testOldSePasswordNull()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/userManage');
            $browser->script('adminSetting.showModal = true');
            $browser->script('adminSetting.newSePassword = ""');
            $browser->script('adminSetting.$validator.validate("new-se-password")');
            $browser->assertVisible('@newSePassword');
        });
    }
    /** 
    * password dialog - new se pass had
    * @group PasswordDialogTest
    * @return void
    */
    public function testNewSePasswordHad()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/userManage');
            $browser->script('adminSetting.showModal = true');
            $browser->script('adminSetting.$validator.validate("new-se-password")');
            $browser->script('adminSetting.newSePassword = "1234567"');
            $browser->assertMissing('@newSePassword');
        });
    }
    /** 
    * password dialog - close button
    * @group PasswordDialogTest
    * @return void
    */
    public function testPasswordDiolagClose()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/userManage');
            $browser->script('adminSetting.showModal = true');
            $browser->script('document.getElementById("changePsClose").click()');
            $browser->assertMissing('@PsChangeDialog');
        });
    }     
    /** 
    * password dialog - passworld not equal
    * @group PasswordDialogTest
    * @return void
    */
    public function testPasswordNotEqual()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/userManage');
            $browser->script('adminSetting.showModal = true');
            $browser->script('adminSetting.oldPassword = "1234"');
            $browser->script('adminSetting.newPassword = "12345"');
            $browser->script('adminSetting.newSePassword = "1234567"');
            $browser->script('document.getElementById("changePsApply").click()');
            $browser->pause(1000);
            $browser->assertVisible('@passwordNotEqual');
        });
    } 
    /** 
    * add sub account dialog - add cheack data,user name empty
    * @group addSubAccountDialogTest
    * @return void
    */
    public function testAddSubAccountBtn()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/userManage');
            $browser->script('userManage.showModal = true');
            $browser->script('userManage.name = ""');
            $browser->script('document.getElementById("dialogUpdateBtn").click()');
            $browser->assertVisible('@userNameWarn');
        });
    }
    /** 
    * add sub account dialog - add cheack data,user name had data
    * @group addSubAccountDialogTest
    * @return void
    */
    public function testAddSubAccountCoBtn()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/userManage');
            $browser->script('userManage.showModal = true');
            $browser->script('userManage.$validator.validate("user-Name")');
            $browser->script('userManage.name = "12345"');
            $browser->assertMissing('@userNameWarn');
        });
    }
}
