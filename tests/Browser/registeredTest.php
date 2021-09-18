<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class registeredTest extends DuskTestCase
{
    /**
     * registered - hope id input test，is empty
     * @group registeredTest
     * @return void
     */
    public function testIdIsEmpty(){
        $this->browse(function (Browser $browser) {
            $browser->visit('/register');
            $browser->script('registeredPage.$validator.validate("apply-id")');
            $browser->assertVisible('@applyId');
        });
    }  
    /**
     * registered - hope id input test，is empty
     * @group registeredTest
     * @return void
     */
    public function testIdIsNoEmpty(){
        $this->browse(function (Browser $browser) {
            $browser->visit('/register');
            $browser->script('registeredPage.$validator.validate("apply-id")');
            $browser->script('registeredPage.applyId = "測試"');
            $browser->assertMissing('@applyId');
        });
    }
    /**
     * registered - admin-name input test，is empty
     * @group registeredTest
     * @return void
     */
    public function testAdminNameIsEmpty(){
        $this->browse(function (Browser $browser) {
            $browser->visit('/register');
            $browser->script('registeredPage.$validator.validate("admin-name")');
            $browser->assertVisible('@adminName');
        });
    }  
    /**
     * registered - hope id input test，is empty
     * @group registeredTest
     * @return void
     */
    public function testAdminNameIsNoEmpty(){
        $this->browse(function (Browser $browser) {
            $browser->visit('/register');
            $browser->script('registeredPage.$validator.validate("admin-name")');
            $browser->script('registeredPage.adminName = "測試"');
            $browser->assertMissing('@adminName');
        });
    }  
    /**
     * registered - Phone input test，is empty
     * @group registeredTest
     * @return void
     */
    public function testPhoneIsEmpty(){
        $this->browse(function (Browser $browser) {
            $browser->visit('/register');
            $browser->script('registeredPage.identity = "personal"');
            $browser->script('registeredPage.$validator.validate("user-tel")');
            $browser->assertVisible('@userTel');
        });
    }    
    /**
     * registered - Phone input test，is not empty and is number
     * @group registeredTest
     * @return void
     */
    public function testPhoneIsNotEmpty()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/register');
            $browser->script('registeredPage.identity = "personal"');
            $browser->script('registeredPage.$validator.validate("user-tel")');
            $browser->script('registeredPage.userTel = "11111111111"');
            $browser->assertMissing('@userTel');
        });
    }   
    /**
     * registered - Phone input test，is not empty and not format
     * @group registeredTest
     * @return void
     */
    public function testPhoneIsNotFormat()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/register');
            $browser->script('registeredPage.identity = "personal"');
            $browser->script('registeredPage.$validator.validate("user-tel")');
            $browser->script('registeredPage.userTel = "11aa111111"');
            $browser->assertVisible('@userTel');
        });
    }      
    /**
     * registered - mail input test，is empty
     * @group registeredTest
     * @return void
     */
    public function testMailIsEmpty(){
        $this->browse(function (Browser $browser) {
            $browser->visit('/register');
            $browser->script('registeredPage.identity = "personal"');
            $browser->script('registeredPage.$validator.validate("user-mail")');
            $browser->assertVisible('@userMail');
        });
    }    
    /**
     * registered - Mail input test，is not empty and is format
     * @return void
     */
    public function testMailIsNotEmpty()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/register');
            $browser->script('registeredPage.identity = "personal"');
            $browser->script('registeredPage.$validator.validate("user-mail")');
            $browser->script('registeredPage.userMail = "123@mail.com"');
            $browser->assertMissing('@userMail');
        });
    }   
    /**
     * registered - MailI test，is not empty and not format
     * @group registeredTest
     * @return void
     */
    public function testMailIIsNotFormat()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/register');
            $browser->script('registeredPage.identity = "personal"');
            $browser->script('registeredPage.userMail = "1231111com"');
            $browser->assertVisible('@userMail');
        });
    } 
    /**
     * registered - ContactTel input test，is empty
     * @group registeredTest
     * @return void
     */
    public function testContactMailTelIsEmpty(){
        $this->browse(function (Browser $browser) {
            $browser->visit('/register');
            $browser->script('registeredPage.identity = "company"');
            $browser->script('registeredPage.$validator.validate("contact-tel")');
            $browser->assertVisible('@contactTel');
        });
    }    
    /**
     * registered - ContactTel input test，is not empty and is number
     * @group registeredTest
     * @return void
     */
    public function testContactMailIsNotEmpty()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/register');
            $browser->script('registeredPage.identity = "company"');
            $browser->script('registeredPage.$validator.validate("contact-tel")');
            $browser->script('registeredPage.contactTel = "11111111111"');
            $browser->assertMissing('@contactTel');
        });
    }   
    /**
     * registered - ContactTel input test，is not empty and not format
     * @group registeredTest
     * @return void
     */
    public function testContactMailIsNotFormat(){
        $this->browse(function (Browser $browser) {
            $browser->visit('/register');
            $browser->script('registeredPage.identity = "company"');
            $browser->script('registeredPage.$validator.validate("contact-mail")');
            $browser->script('registeredPage.contactTel = "11aa111111"');
            $browser->assertVisible('@contactMail');
        });
    }      
    /**
     * registered - contactMail input test，is empty
     * @group registeredTest
     * @return void
     */
    public function testContactMailIsEmpty(){
        $this->browse(function (Browser $browser) {
            $browser->visit('/register');
            $browser->script('registeredPage.identity = "company"');
            $browser->script('registeredPage.$validator.validate("contact-mail")');
            $browser->assertVisible('@contactMail');
        });
    }    
    /**
     * registered - contactMail input test，is not empty and is format
     * @group registeredTest
     * @return void
     */
    public function testContactMailNotEmpty(){
        $this->browse(function (Browser $browser) {
            $browser->visit('/register');
            $browser->script('registeredPage.identity = "company"');
            $browser->script('registeredPage.$validator.validate("contact-mail")');
            $browser->script('registeredPage.contactMail = "123@mail.com"');
            $browser->assertMissing('@contactMail');
        });
    }   
    /**
     * registered - contactMail test，is not empty and not format
     * @group registeredTest
     * @return void
     */
    public function testContactMailNotFormat()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/register');
            $browser->script('registeredPage.identity = "company"');
            $browser->script('registeredPage.$validator.validate("contact-mail")');
            $browser->script('registeredPage.contactMail = "1231111com"');
            $browser->assertVisible('@contactMail');
        });
    }     
    /**
     * registered - identity radio, select personal
     * @group registeredTest
     * @return void
     */
    public function testIdentityPersonal()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/register');
            $browser->script('registeredPage.identity = "personal"');
            $browser->assertVisible('@personalInf');
            $browser->assertVisible('@personalContact');
            $browser->assertMissing('@campanyInf');
            $browser->assertMissing('@campanyContact');
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
            $browser->visit('/register');
            $browser->script('registeredPage.identity = "personal"');
            $browser->script('registeredPage.applyId = "希望id"');
            $browser->script('registeredPage.adminName = "admin"');
            $browser->script('registeredPage.userTel = "0978889019"');
            $browser->script('registeredPage.userMail = "num@gmail.com"');
            $browser->script('document.getElementById("buttonApply").click()');
            $browser->pause(1000);
            $browser->assertPathBeginsWith('/register/complete');
        });
    }
    /**
     * registered - apply button, personal data uncorrect
     * @group registeredTest
     * @return void
     */
    public function testApplyPersonUncorrect()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/register');
            $browser->script('registeredPage.identity = "personal"');
            $browser->script('registeredPage.applyId = ""');
            $browser->script('registeredPage.adminName = "admin"');
            $browser->script('registeredPage.userTel = "0978889019"');
            $browser->script('registeredPage.userMail = "num@gmail.com"');
            $browser->script('document.getElementById("buttonApply").click()');
            $browser->pause(1000);
            $browser->assertPathBeginsWith('/register');
            $browser->assertVisible('@applyId');;
        });
    }  
    /**
     * registered - apply button,company is data correct
     * @group registeredTest
     * @return void
     */
    public function testApplyCompanyCorrect()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/register');
            $browser->script('registeredPage.identity = "company"');
            $browser->script('registeredPage.applyId = "希望id"');
            $browser->script('registeredPage.adminName = "admin"');
            $browser->script('registeredPage.contactPerson = "佐佐木小次郎"');
            $browser->script('registeredPage.contactTel = "0978889019"');
            $browser->script('registeredPage.contactMail = "test@mail.com"');
            $browser->script('document.getElementById("buttonApply").click()');
            $browser->pause(1000);
            $browser->assertPathBeginsWith('/register/complete');
        });
    }
    /**
     * registered - apply button,company is data uncorrect
     * @group registeredTest
     * @return void
     */
    public function testApplyCompanyUncorrect()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/register');
            $browser->script('registeredPage.identity = "company"');
            $browser->script('registeredPage.applyId = ""');
            $browser->script('registeredPage.adminName = "admin"');
            $browser->script('registeredPage.contactPerson = "佐佐木小次郎"');
            $browser->script('registeredPage.contactTel = "0978889019"');
            $browser->script('registeredPage.contactMail = "test@mail.com"');
            $browser->script('document.getElementById("buttonApply").click()');
            $browser->pause(1000);
            $browser->assertPathBeginsWith('/register');
            $browser->assertVisible('@applyId');;
        });
    }  
    /**
     * registered - cheack box uncheack,apply button display none
     * @group registeredTest
     * @return void
     */
    public function testAgreeRule()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/register');
            $browser->script('registeredPage.identity = "personal"');
            $browser->script('registeredPage.agree = true');
            $browser->pause(1000);
            $browser->assertPathBeginsWith('/register');
            $browser->assertVisible('@applyId');;
        });
    }  
}
