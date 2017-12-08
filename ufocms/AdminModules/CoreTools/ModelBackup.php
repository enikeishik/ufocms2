<?php
/**
 * @copyright
 */

namespace Ufocms\AdminModules\CoreTools;

/**
 * Stateless model class
 */
class ModelBackup extends \Ufocms\AdminModules\StatelessModel
{
    /**
     * @see parent
     */
    protected function init()
    {
        $this->config->registerAction('backupsystem');
        $this->config->registerMakeAction('backupsystem');
        $this->config->registerAction('backupuser');
        $this->config->registerMakeAction('backupuser');
    }
    
    /**
     * Make backup of system items.
     */
    public function backupsystem()
    {
        $this->config->load(__DIR__ . '/config.php');
        $this->backup('system', $this->config->systemBackups);
    }
    
    /**
     * Make backup of user items.
     */
    public function backupuser()
    {
        $this->config->load(__DIR__ . '/config.php');
        $this->backup('user', $this->config->userBackups);
    }
    
    /**
     * Make backup defined by $backupSet into archive with $name.
     * @param string $name
     * @param array $backupSet
     */
    protected function backup($name, array $backupSet)
    {
        $bk = new Backuper($this->config, $name);
        try {
            $bk->backup($backupSet);
            $path = $bk->getBackupPath();
            ob_end_clean();
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . basename($path) . '"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($path));
            if (false === @readfile($path)) {
                throw new \Exception('Can not read backup file ' . $bk->getBackupUrl());
            }
        } catch (\Exception $e) {
            $this->result = $e->getMessage();
            return;
        } finally {
            try {
                $bk->cleanup();
            } catch (\Exception $e) {
                
            } 
        }
        exit();
    }
}
