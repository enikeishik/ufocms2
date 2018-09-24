<?php
namespace Helper;

// here you can define custom actions
// all public methods declared in helper class will be available in $I

class Acceptance extends \Codeception\Module
{
    public function grabElements($locator)
    {
        return $this->getModule('PhpBrowser')->_findElements($locator);
    }
    
    public function getBrowser()
    {
        return $this->getModule('PhpBrowser');
    }
    
    public function getGuzzle()
    {
        return $this->getModule('PhpBrowser')->guzzle;
    }
    
    public function getClient()
    {
        return $this->getModule('PhpBrowser')->client;
    }
}
