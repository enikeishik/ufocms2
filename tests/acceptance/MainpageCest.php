<?php


class MainpageCest
{
    public function _before(AcceptanceTester $I)
    {
    }

    public function _after(AcceptanceTester $I)
    {
    }

    // tests
    public function mainpageWorks(AcceptanceTester $I)
    {
        $I->amOnPage('/');
        $I->see('Главная');
        $I->seeElement('#header');
        $I->seeElement('#content');
        $I->seeElement('#footer');
    }
    
    public function otherpagesWorks(AcceptanceTester $I)
    {
        $baseUrl = $I->getBrowser()->_getUrl();
        $I->amOnPage('/');
        $links = $I->grabElements('a');
        foreach ($links as $link) {
            $href = $link->getAttribute('href');
            $parts = explode('/', $href);
            if (
                $href == '/'
                || 0 === strpos($href, 'http')
                || false !== strpos($parts[count($parts) - 1], '.')
            ) {
                continue;
            }
            
            $I->amGoingTo('Testing page ' . $href); 
            $I->amOnPage($href);
            $I->dontSeeResponseCodeIs(\Codeception\Util\HttpCode::INTERNAL_SERVER_ERROR);
        }
    }
}
