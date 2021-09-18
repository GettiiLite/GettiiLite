<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Faker\Factory as Faker;

class EventsInfoBasisTest extends DuskTestCase
{

    /**
     * A Dusk test example.
     *@group specification
     * @return void
     */
    public function testEventTitleCorrect()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/events/info');
            $browser->script('$("#basisInfPage").click();');
            $faker = Faker::create('zh_TW');
            $title = $faker->realText($maxNbChars = 20, $indexSize = 2).'title';

            $browser->type('#eventTitle',  $title);
            $browser->script('basisSetting.$validator.validateAll();');
            $value = $browser->text('@eventTitleValidate');
            $this->assertEquals('', $value);
        });
    }

    public function testEventTitleValidate()
    {
        $this->browse(function (Browser $browser) {
            $browser->clear('#eventTitle');
            $browser->script('basisSetting.$validator.validateAll();');
            $value = $browser->text('@eventTitleValidate');
            $this->assertEquals('The event-title field is required.', $value);
        });
    }

    public function testEventTitleMaxValidate()
    {
        $this->browse(function (Browser $browser) {

            $text = '';
            for($n=0; $n<120; $n++){
                $text .= 'test';
            }
            
            $browser->script('$("#basisSetting").click();');
            $browser->type('#eventTitle',  $text);
           
            $value = $browser->text('@eventTitleValidate');
            $this->assertEquals('The event-title field may not be greater than 255 characters.', $value);
        });
    }

    public function testEventSubTitleCorrect()
    {
        $this->browse(function (Browser $browser) {
            $faker = Faker::create('zh_TW');
            $subTitle = $faker->realText($maxNbChars = 20, $indexSize = 2).'title';

            $browser->type('#eventSubTitle',  $subTitle);
            $browser->script('basisSetting.$validator.validateAll();');
            $value = $browser->text('@eventSubTitleValidate');
            $this->assertEquals('', $value);
        });
    }

    public function testEventSubTitleMaxValidate()
    {
        $this->browse(function (Browser $browser) {

            $text = '';
            for($n=0; $n<120; $n++){
                $text .= 'test';
            }
            
            $browser->script('$("#basisSetting").click();');
            $browser->type('#eventSubTitle',  $text);
           
            $value = $browser->text('@eventSubTitleValidate');
            $this->assertEquals('The event-sub-title field may not be greater than 255 characters.', $value);
        });
    }

    public function testEventTypeValidate()
    {
        $this->browse(function (Browser $browser) {
            $browser->script('$("#basisSetting").click();');
            $browser->script('basisSetting.$validator.validateAll();');
            $value = $browser->text('@evenTypeValidate');
            $this->assertEquals('The evenType field is required.', $value);
        });
    }

    
    public function testCustomerInformationTitleCorrect()
    {
        $this->browse(function (Browser $browser) {
            $faker = Faker::create('zh_TW');
            $title = $faker->realText($maxNbChars = 20, $indexSize = 2).'title';

            $browser->type('#customerInfTitle',  $title);
            $browser->script('basisSetting.$validator.validateAll();');
            $value = $browser->text('@customerInfTitleValidate');
            $this->assertEquals('', $value);
        });
    }

    public function testCustomerInformationTitleValidate()
    {
        $this->browse(function (Browser $browser) {
            $browser->script('$("#basisSetting").click();');
            $browser->clear('#customerInfTitle');
            $browser->script('basisSetting.$validator.validateAll();');
            $value = $browser->text('@customerInfTitleValidate');
            $this->assertEquals('The title field is required.', $value);
        });
    }

    public function  testCustomerInformationEmailCorrect()
    {
        $this->browse(function (Browser $browser) {
            $faker = Faker::create('zh_TW');
            $email = $faker->email;
            
            $browser->type('#customerInfEmail',  $email);
            $browser->script('basisSetting.$validator.validateAll();');
            $value = $browser->text('@customerInfEmailValidate');
            $this->assertEquals('', $value);
        });
    }

    public function testCustomerInformationEmailValidate()
    {
        $this->browse(function (Browser $browser) {
            $browser->script('$("#basisSetting").click();');
            $browser->clear('#customerInfEmail');
            $browser->script('basisSetting.$validator.validateAll();');
            $value = $browser->text('@customerInfEmailValidate');
            $this->assertEquals('The email field is required.', $value);
        });
    }

    public function testCustomerInformationEmailTypeValidate()
    {
        $this->browse(function (Browser $browser) {
            $browser->script('$("#basisSetting").click();');
            $browser->type('email', '123456');
            $browser->script('basisSetting.$validator.validateAll();');
            $value = $browser->text('@customerInfEmailValidate');
            $this->assertEquals('The email field must be a valid email.', $value);
        });
    }

    public function testCustomerInformationTelCorrect()
    {
        $this->browse(function (Browser $browser) {
            $faker = Faker::create('zh_TW');
            $tel= $faker->VAT;
            
            $browser->type('#customerInfTel',  $tel);
            $browser->script('basisSetting.$validator.validateAll();');
            $value = $browser->text('@customerInfTelValidate');
            $this->assertEquals('', $value);
        });
    }

    public function testCustomerInformationTelValidate()
    {
        $this->browse(function (Browser $browser) {
            $browser->script('$("#basisSetting").click();');
            $browser->clear('#customerInfTel');
            $browser->script('basisSetting.$validator.validateAll();');
            $value = $browser->text('@customerInfTelValidate');
            $this->assertEquals('The tel field is required.', $value);
        });
    }

    public function testCustomerInformationTelTypeValidate()
    {
        $this->browse(function (Browser $browser) {
            $browser->script('$("#basisSetting").click();');
            $browser->type('#customerInfTel',  'text');
            $value = $browser->text('@customerInfTelValidate');
            $this->assertEquals('The tel field may only contain numeric characters.', $value);
        });
    }

    public function testCustomerInformationUrlCorrect()
    {
        $this->browse(function (Browser $browser) {
            $faker = Faker::create('zh_TW');
            $url = $faker->url;
           
            $browser->type('#customerInfUrl',  $url);
            $browser->script('basisSetting.$validator.validateAll();');
            $value = $browser->text('@customerInfUrlValidate');
            $this->assertEquals('', $value);
        });
    }

    public function testCustomerInformationUrlValidate()
    {
        $this->browse(function (Browser $browser) {
            $browser->script('$("#basisSetting").click();');
            $browser->clear('#customerInfUrl');
            $browser->script('basisSetting.$validator.validateAll();');
            $value = $browser->text('@customerInfUrlValidate');
            $this->assertEquals('The event-url field is required.', $value);
        });
    }

    public function testCustomerInformationUrlTypeValidate()
    {
        $this->browse(function (Browser $browser) {
            $browser->type('#customerInfUrl',  'text');
            $value = $browser->text('@customerInfUrlValidate');
            $this->assertEquals('The event-url field is not a valid URL.', $value);
        });
    }

    public function testPlaceTitleCorrect()
    {
        $this->browse(function (Browser $browser) {
            $faker = Faker::create('zh_TW');
            $title = $faker->realText($maxNbChars = 20, $indexSize = 2).'title';
           
            $browser->type('#placeTitle',  $title);
            $browser->script('basisSetting.$validator.validateAll();');
            $value = $browser->text('@placeTitleValidate');
            $this->assertEquals('', $value);
        });
    }

    public function testPlaceTitleValidate()
    {
        $this->browse(function (Browser $browser) {
            $browser->clear('#placeTitle');
            $browser->script('basisSetting.$validator.validateAll();');
            $value = $browser->text('@placeTitleValidate');
            $this->assertEquals('The place-name field is required.', $value);
        });
    }

    public function testPlaceTitleMaxValidate()
    {
        $this->browse(function (Browser $browser) {

            $text = '';
            for($n=0; $n<50; $n++){
                $text .= 'test';
            }
            
            $browser->type('#placeTitle',  $text);
            $value = $browser->text('@placeTitleValidate');
            $this->assertEquals('The place-name field may not be greater than 80 characters.', $value);
        });
    }

    public function testPlaceKanaTitleValidate()
    {
        $this->browse(function (Browser $browser) {
            $browser->clear('#placeKanaTitle');
            $browser->script('basisSetting.$validator.validateAll();');
            $value = $browser->text('@placeKanaTitleValidate');
            $this->assertEquals('The place-name-kana field is required.', $value);
        });
    }

    public function testPlaceUrlCorrect()
    {
        $this->browse(function (Browser $browser) {
            $faker = Faker::create('zh_TW');
            $url = $faker->url;
           
            $browser->type('##placeUrl',  $url);
            $browser->script('basisSetting.$validator.validateAll();');
            $value = $browser->text('@placeUrlValidate');
            $this->assertEquals('', $value);
        });
    }

    public function testPlaceUrlValidate()
    {
        $this->browse(function (Browser $browser) {
            $browser->clear('#placeUrl');
            $browser->script('basisSetting.$validator.validateAll();');
            $value = $browser->text('@placeUrlValidate');
            $this->assertEquals('The place-url field is required.', $value);
        });
    }

    public function testPlaceUrlTypeValidate()
    {
        $this->browse(function (Browser $browser) {
            $browser->type('#placeUrl',  'text');
            $value = $browser->text('@placeUrlValidate');
            $this->assertEquals('The place-url field is not a valid URL.', $value);
        });
    }

    public function testInfOpenDateValidate()
    {
        $this->browse(function (Browser $browser) {
            $value = $browser->value('#infOpenDate');
            $browser->script('basisSetting.$validator.validateAll();');
            $value = $browser->text('@infOpenDate');
            $this->assertEquals('The inf-open-date field is required.', $value);
        });
    }

    public function testInfOpenDateNull()
    {
        $this->browse(function (Browser $browser) {
            $earlyBirdTicket = $browser->attribute('#earlyBirdTicketDate', 'disabled');
            $this->assertEquals('true', $earlyBirdTicket);

            $earlyBirdTicket = $browser->attribute('#normalTicketDate', 'disabled');
            $this->assertEquals('true', $earlyBirdTicket);
        });
    }
}
