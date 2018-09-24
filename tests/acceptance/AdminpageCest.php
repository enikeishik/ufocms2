<?php


class AdminpageCest
{
    public function _before(AcceptanceTester $I)
    {
    }

    public function _after(AcceptanceTester $I)
    {
    }
    
    protected function checkSource(AcceptanceTester $I)
    {
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK);
        $I->dontSeeInSource('<input type="text" name="login"');
        $I->dontSeeInSource('<input type="password" name="password"');
        $I->seeInSource('<div id="frameleft">');
        $I->seeInSource('<div id="logout">');
        $I->seeInSource('<div id="sections" class="sections">');
        $I->seeInSource('<div id="coresections">');
        $I->seeInSource('<div id="framemain">');
        $I->seeInSource('</body>');
    }

    // tests
    public function adminpageWorks(AcceptanceTester $I)
    {
        $I->amOnPage('/admin');
        $I->submitForm('form', ['login' => 'admin', 'password' => 'admin']);
        $this->checkSource($I);
    }
    
    public function otherpagesWorks(AcceptanceTester $I)
    {
        $baseUrl = $I->getBrowser()->_getUrl();
        $I->amOnPage('/admin');
        $I->submitForm('form', ['login' => 'admin', 'password' => 'admin']);
        $links = $I->grabElements('a');
        foreach ($links as $link) {
            $href = $link->getAttribute('href');
            $parts = explode('/', $href);
            if (
                $href == ''
                || $href == '/'
                || 0 === strpos($href, 'http')
                || false !== strpos($parts[count($parts) - 1], '.')
                || false !== strpos($href, 'action=adminlogout')
            ) {
                continue;
            }
            
            $I->amGoingTo('Testing page ' . $href); 
            $I->amOnPage('/admin/' . $href);
            $this->checkSource($I);
        }
    }
}
