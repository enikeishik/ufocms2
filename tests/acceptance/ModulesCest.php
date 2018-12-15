<?php


class ModulesCest
{
    /**
     * @var string
     */
    const MODULES_ENTRY = '/modules';
    
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
    
    public function _before(AcceptanceTester $I)
    {
        $this->fillDb($I);
    }

    public function _after(AcceptanceTester $I)
    {
    }
    
    protected function fillDb(AcceptanceTester $I)
    {
        $I->haveInDatabase(
            'sections', 
            [
                'id'        => 4001, 
                'topid'     => 4001, 
                'parentid'  => 0, 
                'moduleid'  => 4, 
                'path'      => '/test-board-module-path/', 
                'indic'     => 'Test Board Module Section', 
                'isenabled' => 1, 
            ]
        );
        $I->haveInDatabase(
            'board_sections', 
            [
                'Id'            => 4001, 
                'SectionId'     => 4001, 
                'BodyHead'      => 'Test Board Sections Header 1', 
                'BodyFoot'      => 'Test Board Sections Footer 1', 
                'PageLength'    => 15, 
            ]
        );
        $I->haveInDatabase(
            'board', 
            [
                'Id'            => 4001, 
                'SectionId'     => 4001, 
                'DateCreate'    => date('Y-m-d H:00:00'), 
                'Title'         => 'Test Board Title 1', 
                'Message'       => 'Test Board Message 1', 
                'Contacts'      => 'Test Board Contacts 1', 
                'IsHidden'      => 0, 
            ]
        );
        
        $I->haveInDatabase(
            'sections', 
            [
                'id'        => 6001, 
                'topid'     => 6001, 
                'parentid'  => 0, 
                'moduleid'  => 6, 
                'path'      => '/test-faq-module-path/', 
                'indic'     => 'Test FAQ Module Section', 
                'isenabled' => 1, 
            ]
        );
        $I->haveInDatabase(
            'faq_sections', 
            [
                'Id'            => 6001, 
                'SectionId'     => 6001, 
                'BodyHead'      => 'Test FAQ Sections Header 1', 
                'BodyFoot'      => 'Test FAQ Sections Footer 1', 
                'PageLength'    => 15, 
            ]
        );
        $I->haveInDatabase(
            'faq', 
            [
                'Id'            => 6001, 
                'SectionId'     => 6001, 
                'DateCreate'    => date('Y-m-d 00:00:00'), 
                'DateAnswer'    => date('Y-m-d H:00:00'), 
                'USign'         => 'Test FAQ Question Sign 1', 
                'UMessage'      => 'Test FAQ Question Message 1', 
                'ASign'         => 'Test FAQ Answer Sign 1', 
                'AMessage'      => 'Test FAQ Answer Message 1', 
                'IsHidden'      => 0, 
            ]
        );
        
        $I->haveInDatabase(
            'sections', 
            [
                'id'        => 2001, 
                'topid'     => 2001, 
                'parentid'  => 0, 
                'moduleid'  => 2, 
                'path'      => '/test-news-module-path/', 
                'indic'     => 'Test News Module Section', 
                'isenabled' => 1, 
            ]
        );
        $I->haveInDatabase(
            'news_sections', 
            [
                'Id'            => 2001, 
                'SectionId'     => 2001, 
                'BodyHead'      => 'Test News Sections Header 1', 
                'BodyFoot'      => 'Test News Sections Footer 1', 
                'PageLength'    => 15, 
            ]
        );
        $I->haveInDatabase(
            'news', 
            [
                'Id'            => 2001, 
                'SectionId'     => 2001, 
                'DateCreate'    => date('Y-m-d H:00:00'), 
                'Title'         => 'Test News Title 1', 
                'Author'        => 'Test News Author 1', 
                'Icon'          => '', 
                'Announce'      => '', 
                'Body'          => '', 
                'ViewedCnt'     => 0, 
                'IsHidden'      => 0, 
                'IsRss'         => 1, 
            ]
        );
    }
    
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
