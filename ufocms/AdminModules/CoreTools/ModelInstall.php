<?php
/**
 * @copyright
 */

namespace Ufocms\AdminModules\CoreTools;

/**
 * Stateless model class
 */
class ModelInstall extends \Ufocms\AdminModules\StatelessModel
{
    /**
     * @see parent
     */
    protected function init()
    {
        $this->config->registerAction('installmodule');
        $this->config->registerMakeAction('installmodule');
    }
    
    /**
     * Make backup of system items.
     */
    public function installmodule()
    {
        if (empty($_POST['url'])) {
            $this->result = 'Source URL not set or empty';
            return;
        }
        
        $this->config->load(__DIR__ . '/config.php');
        $inst = new Installer($this->config, $this->db);
        if (!$inst->isInstallModulePossible()) {
            $this->result = 'Destination path(s) not exists or have not write permission';
            return;
        }
        
        try {
            $inst->installModuleFromUrl($_POST['url']);
            $this->result = 'Module installed successfully';
        } catch (\Exception $e) {
            $this->result = $e->getMessage();
        } finally {
            try {
                $inst->cleanup();
            } catch (\Exception $e) {
                
            } 
        }
    }
}
