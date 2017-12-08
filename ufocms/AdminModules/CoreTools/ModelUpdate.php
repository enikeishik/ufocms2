<?php
/**
 * @copyright
 */

namespace Ufocms\AdminModules\CoreTools;

/**
 * Stateless model class
 */
class ModelUpdate extends \Ufocms\AdminModules\StatelessModel
{
    /**
     * @var UpdateChecker
     */
    protected $updateChecker = null;
    
    /**
     * @var int
     */
    protected $localUpDate = 0;
    
    /**
     * var int
     */
    protected $repositoryUpDate = 0;
    
    /**
     * @see parent
     */
    protected function init()
    {
        $this->updateChecker = new UpdateChecker($this->config);
    }
    
    /**
     * @return bool
     */
    public function isOutofdate()
    {
        return $this->localUpDate < $this->repositoryUpDate;
    }
    
    /**
     * @return bool
     */
    public function isCanAutoUpdate()
    {
        return $this->updateChecker->isCanAutoUpdate();
    }
    
    /**
     * @return string
     */
    public function getLocalSystemDate()
    {
        try {
            $this->localUpDate = $this->updateChecker->getLocalSystemDate();
            return date('d-m-Y', $this->localUpDate);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
    
    /**
     * @return string
     */
    public function getRepositoryDate()
    {
        try {
            $this->repositoryUpDate = $this->updateChecker->getRepositoryDate();
            return date('d-m-Y', $this->repositoryUpDate);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}
