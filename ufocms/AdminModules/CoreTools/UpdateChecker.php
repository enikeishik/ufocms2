<?php
/**
 * @copyright
 */

namespace Ufocms\AdminModules\CoreTools;

use Ufocms\Backend\Config;

/**
 * Update checker
 */
class UpdateChecker
{
    protected $source = '';
    
    protected $local = '';
    
    /**
     * @param Config $config
     */
    public function __construct(Config $config)
    {
        $config->load(__DIR__ . '/config.php');
        $this->local = $config->rootPath . $config->localUpdateCheck;
        $this->source = $config->repositoryApiUrl . '?since=' . date('Y-m-d', time() - 3600 * 24 * 356) . 'T00:00:00';
    }
    
    /**
     * @return bool
     */
    public function isCanAutoUpdate()
    {
        return is_writable($this->local);
    }
    
    /**
     * @return int
     */
    public function getRepositoryDate()
    {
        return $this->getDataUpDate($this->getRemoteData($this->source));
    }
    
    /**
     * @return int
     */
    public function getLocalSystemDate()
    {
        return $this->getFileUpDate($this->local);
    }
    
    /**
     * @param string $path
     * @return int
     * @throws \Exception
     */
    protected function getFileUpDate($path)
    {
        if (!file_exists($path)) {
            throw new \Exception('Local path not exists ' . $path);
        }
        /* TODO: check on *nix systems!
        echo time() - filemtime($path);
        echo '<br>';
        echo time() - filectime($path);
        */
        $ts = filemtime($path);
        if (false === $ts) {
            throw new \Exception('Can not get change time for local path ' . $path);
        }
        return $ts;
    }
    
    /**
     * Download data from remote source.
     * @param string $source
     * @return string
     * @throws \Exception
     */
    protected function getRemoteData($source)
    {
        $options = [
            'http' => [
                'method' => 'GET',
                'header' => "User-Agent: Mozilla/5.0\r\n"
            ]
        ];
        $context = stream_context_create($options);
        if (false === $content = @file_get_contents($source, false, $context)) {
            throw new \Exception('Error while get source');
        }
        return $content;
    }
    
    /**
     * Gets date of last data update.
     * @param string $data
     * @return int
     * @throws \Exception
     */
    protected function getDataUpDate($data)
    {
        $json = json_decode($data);
        if (null === $json) {
            throw new \Exception('Error while decode JSON');
        }
        if (!isset($json[0]->commit->committer->date)) {
            throw new \Exception('JSON object not contain requested fields');
        }
        $ts = strtotime($json[0]->commit->committer->date);
        if (false === $ts) {
            throw new \Exception('Can not convert data into timestamp');
        }
        return $ts;
    }
    
}
