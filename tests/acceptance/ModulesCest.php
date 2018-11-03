<?php


class ModulesCest
{
    protected const MODULES_ENTRY = '/modules';
    
    public function _before(AcceptanceTester $I)
    {
    }

    public function _after(AcceptanceTester $I)
    {
    }
    
    /**
     * @var array
     */
    protected $modules = [
        ['/board', 'html'], 
        ['/board/rss', 'rss'], 
        ['/faq', 'html'], 
        ['/faq/rss', 'rss'], 
        ['/news', 'html'], 
        ['/news/rss', 'rss'], 
        ['/news/yandex', 'rss'], 
        ['/news/rambler', 'rss'], 
        ['/news/yadzen', 'rss'], 
    ];
    
    /**
     * @return array
     */
    protected function modulesAvailableData()
    {
        $arr = [];
        foreach ($this->modules as $page) {
            $arr[] = [self::MODULES_ENTRY . $page[0], $page[1]];
        }
        return $arr;
    }
    
    // tests
    public function modulesPageWorks(AcceptanceTester $I)
    {
        $I->amOnPage(self::MODULES_ENTRY);
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::FORBIDDEN);
        $I->dontSeeInSource('Fatal error');
        $I->dontSeeInSource('Warning');
        $I->dontSeeInSource('Notice');
    }
    
    /**
     * @dataProvider modulesAvailableData
     */
    public function modulePageWorks(AcceptanceTester $I, \Codeception\Example $example)
    {
        $I->amGoingTo('Testing page ' . $example[0]); 
        $I->amOnPage($example[0]);
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK);
        $I->dontSeeInSource('Fatal error');
        $I->dontSeeInSource('Warning');
        $I->dontSeeInSource('Notice');
        if ('rss' == $example[1]) {
            $I->seeElement('rss');
            $I->seeElement('channel');
            $I->seeElement('item');
        } else {
            $I->seeElement('#header');
            $I->seeElement('#content');
            $I->seeElement('#footer');
        }
    }
}
