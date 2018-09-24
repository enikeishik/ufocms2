<?php
require_once __DIR__  . DIRECTORY_SEPARATOR . '_presets.php';

use \Ufocms\Frontend\Config;
use \Ufocms\Frontend\Messenger;

class FrontendMessengerTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;
    
    protected function _before()
    {
    }

    protected function _after()
    {
    }

    // tests
    public function testSendEmail()
    {
$tpl = <<<EOD
namespace Ufocms\\Frontend;
function mail(\$to, \$subject, \$message, \$headers)
{
    return [
        'to' => \$to, 
        'subject' => \$subject, 
        'message' => \$message, 
        'headers' => \$headers
    ];
}
function fopen(\$file, \$flags)
{
    return 1;
}
function filesize(\$file)
{
    return 0;
}
function fread(\$hndl)
{
    return '';
}
function fwrite(\$hndl, \$data)
{
    return 1;
}
function fclose(\$hndl)
{
    return true;
}
EOD;
        eval($tpl);
        $config = new Config();
        $messenger = new Messenger($config);
        
        $ret = $messenger->sendEmail('test@test.test', 'test subject', 'test message');
        $this->assertTrue('test@test.test' == $ret['to']);
        $this->assertTrue('test subject' == $ret['subject']);
        $this->assertNotFalse(strpos($ret['message'], '<title>test subject</title>'));
        $this->assertNotFalse(strpos($ret['headers'], 'Content-type: '));
        
        $ret = $messenger->sendEmail(
            'test@test.test', 
            'test subject', 
            'test message', 
            [
                ['Path' => '/path_to/first/file', 'Name' => 'First file'], 
                ['Path' => '/path_to/second/file', 'Name' => 'Second file'], 
            ]
        );
        $this->assertNotFalse(strpos($ret['headers'], 'Content-Type: multipart/mixed; boundary='));
        $this->assertNotFalse(strpos($ret['message'], 'filename=First file'));
        $this->assertNotFalse(strpos($ret['message'], 'filename=Second file'));
    }
}
