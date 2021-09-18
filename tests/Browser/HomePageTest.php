<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class HomePageTest extends DuskTestCase
{
    /**
     * 測試是否正常載入
     * @group HomePageTest
     * @return void
     */
    public function testPageIsValid()
    {
        $this->browse(function (Browser $browser) {
            //Given
            //In home page
            $browser->visit('/');

            //Then
            //Page have title
            $browser->assertTitle('Gettii Lite ｜ HomePage');
        });
    }
    /**
     * 測試登錄按鈕功能
     * @group HomePageTest
     * @return void
     */
    public function testLoginButton()
    {
        $this->browse(function (Browser $browser) {
            //Given
            //In home page
            $browser->visit('/');

            //When
            //Click login button
            $browser->click('@login');

            //Then
            //Redirect to login page
            $browser->assertPathIs('/login');
        });
    }
    /**
     * 測試常見問題按鈕功能
     * @group HomePageTest
     * @return void
     */
    public function testFaqsButton()
    {
        $this->browse(function (Browser $browser) {
            //Given
            //In home page
            $browser->visit('/')
                    ->driver->executeScript('window.location.hash = "#contact";');

            //When
            //Click faqs button
            $browser->pause(1000)
                    ->click('@faqs');

            //Then
            //Redirect to faqs page
            $browser->pause(1000)
                    ->assertPathIs('/faqs.html');
        });
    }
}
