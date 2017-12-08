<?php
/**
 * @copyright
 */

namespace Ufocms\AdminModules\CoreTools;

use Ufocms\Backend\Config;

/**
 * Backup functionality
 */
class Backuper
{
    /**
     * Site root path.
     * @var string
     */
    protected $root = '';
    
    /**
     * Length of root path.
     * @var int
     */
    protected $rootLength = 0;
    
    /**
     * Path to backups dir.
     * @var string
     */
    protected $backupsDir = '';
    
    /**
     * Format string for date function to generate backup filename suffix.
     * @var string
     */
    protected $backupSuffix = '_Ymd';
    
    /**
     * Extention of backup archive.
     * @var string
     */
    protected $backupExt = 'zip';
    
    /**
     * Full path to backup file, with file name and ext.
     * @var string
     */
    protected $backupPath = '';
    
    /**
     * Full path mask to backup file(s), to make cleanup.
     * @var string
     */
    protected $backupPathMask = '';
    
    /**
     * @param Config $config
     * @param string $backupName
     */
    public function __construct(Config $config, $backupName)
    {
        $this->root = $config->rootPath;
        $this->rootLength = strlen($this->root);
        $this->backupsDir = $this->root . $config->tmpDir;
        $suffix = date($this->backupSuffix);
        $this->backupPath = $this->backupsDir . '/' . $backupName . $suffix . '.' . $this->backupExt;
        $this->backupPathMask = $this->backupsDir . '/' . $backupName . str_repeat('?', strlen($suffix)) . '.' . $this->backupExt;
    }
    
    /**
     * @return string
     */
    public function getBackupPath()
    {
        return $this->backupPath;
    }
    
    /**
     * @return string
     */
    public function getBackupUrl()
    {
        return substr($this->backupPath, $this->rootLength);
    }
    
    /**
     * @param array $include
     * @param array $exclude = null
     * throws \Exception
     */
    public function backup(array $include, array $exclude = null)
    {
        $this->checkBackupDir();
        
        $zip = new \ZipArchive();
        if (true !== $zip->open($this->backupPath, \ZipArchive::CREATE)) {
            throw new \Exception('Error while open archive ' . $this->backupPath);
        }
        
        foreach ($include as $backupElement) {
            $this->backupWalker($this->root . $backupElement, $zip, $exclude);
        }
        
        $zip->close();
    }
    
    /**
     * throws \Exception
     */
    public function cleanup()
    {
        foreach (glob($this->backupPathMask) as $path) {
            if (!@unlink($path)) {
                throw new \Exception('Error deleting ' . $path);
            }
        }
    }
    
    /**
     * throws \Exception
     */
    protected function checkBackupDir()
    {
        if (!is_writable($this->backupsDir)) {
            throw new \Exception('Backups dir is not writable ' . $this->backupsDir);
        }
    }
    
    /**
     * @param string $path
     * @return string
     */
    protected function getRelativePath($path)
    {
        return substr($path, $this->rootLength);
    }
    
    /**
     * @param string $backupElement
     * @param ZipArchive &$zip
     * @param array $exclude = null
     */
    protected function backupWalker($backupElement, &$zip, array $exclude = null)
    {
        if (is_file($backupElement)) {
            $zip->addFile($backupElement, $this->getRelativePath($backupElement));
            return;
        }
        if (!is_dir($backupElement)) {
            return;
        }
        
        if (false === $dh = @opendir($backupElement)) {
            throw new \Exception('Error opening ' . $backupElement);
        }
        while (false !== $file = readdir($dh)) {
            if ('.' == $file || '..' == $file) {
                continue;
            }
            $path = $backupElement . '/' . $file;
            $pathRelative = $this->getRelativePath($path);
            if (null !== $exclude) {
                $continue = false;
                foreach ($exclude as $ex) {
                    if (0 === strpos($pathRelative, $ex)) {
                        $continue = true;
                        break;
                    }
                }
                if ($continue) {
                    continue;
                }
            }
            if (is_dir($path)) {
                $zip->addEmptyDir($pathRelative);
                $this->backupWalker($path, $zip);
            } else if (is_file($path)) {
                $zip->addFile($path, $pathRelative);
            }
        }
        closedir($dh);
    }
}
