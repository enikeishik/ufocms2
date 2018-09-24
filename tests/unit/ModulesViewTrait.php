<?php
trait ModulesViewTrait
{
    protected $tmpfname;
    protected $testContent;
    protected function init()
    {
        parent::init();
        $this->tmpfname = tempnam(sys_get_temp_dir(), 'tmp');
    }
    public function __destruct()
    {
        unlink($this->tmpfname);
    }
    public function setTestContent($content)
    {
        $this->testContent = $content;
    }
    protected function getLayout()
    {
        file_put_contents($this->tmpfname, $this->testContent);
        return $this->tmpfname;
    }
}
