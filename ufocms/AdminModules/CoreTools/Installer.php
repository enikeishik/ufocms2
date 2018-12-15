<?php
/**
 * @copyright
 */

namespace Ufocms\AdminModules\CoreTools;

use Ufocms\Backend\Config;
use Ufocms\Backend\Db;

/**
 * Modules, widgets, ... installer class
 */
class Installer
{
    /**
     * Tmp file name.
     * @var string
     */
    const TMP_FILE_NAME = '~install';
    
    /**
     * Format string for date function to generate tmp filename suffix.
     * @var string
     */
    const TMP_SUFFIX = '_YmdHis';
    
    /**
     * Extention of tmp archive.
     * @var string
     */
    const TMP_EXTENSION = 'zip';
    
    /**
     * Prefix for table name in SQL file.
     * @var string
     */
    const SQL_TABLE_PREFIX = '/* TABLE_PREFIX */';
    
    /**
     * Common config.
     * @var Config
     */
    protected $config = '';
    
    /**
     * Common db object.
     * @var Db
     */
    protected $db = '';
    
    /**
     * Site root path.
     * @var string
     */
    protected $root = '';
    
    /**
     * Full path to temp dir.
     * @var string
     */
    protected $tempDir = '';
    
    /**
     * Full path to temp file.
     * @var string
     */
    protected $tempFile = '';
    
    /**
     * @param Config $config
     * @param string $backupName
     */
    public function __construct(Config $config, Db $db)
    {
        $this->config = $config;
        $this->db = $db;
        
        $this->root = $config->rootPath;
        $this->setTemp($this->root . $config->tmpDir);
    }
    
    /**
     * @param string $systemTempDir
     */
    protected function setTemp($systemTempDir)
    {
        $this->tempDir = $systemTempDir . '/' . self::TMP_FILE_NAME . date(self::TMP_SUFFIX);
        $this->tempFile = $this->tempDir . '.' . self::TMP_EXTENSION;
    }
    
    /**
     * Checks existings and write permissions on destination.
     * @return bool
     */
    public function isInstallModulePossible()
    {
        foreach ($this->config->moduleStruct['dir'] as $dir) {
            $path = $this->root . $dir;
            if (!is_dir($path) || !is_writable($path)) {
                return false;
            }
        }
        return true;
    }
    
    /**
     * @param string $moduleName
     * @return bool
     */
    public function isInstallingModuleExists($moduleName)
    {
        foreach ($this->config->moduleStruct['dir'] as $dir) {
            if ($dir == strtolower($dir)) {
                $path = $this->root . $dir . $moduleName;
            } else {
                $path = $this->root . $dir . ucfirst($moduleName);
            }
            
            if (file_exists($path)) {
                return true;
            }
        }
        return false;
    }
    
    /**
     * @param string $url
     * @throws \Exception
     */
    public function installModuleFromUrl($url)
    {
        $this->saveRemoteFile($url, $this->tempFile);
        $this->installModuleFromArchive($this->tempFile, true);
    }
    
    /**
     * @param string $archivePath
     * @param bool $cleanup = false
     * @throws \Exception
     */
    public function installModuleFromArchive($archivePath, $cleanup = false)
    {
        $this->extractArchive($archivePath, $this->tempDir);
        if ($cleanup) {
            @unlink($archivePath);
        }
        
        $this->installModule($this->tempDir, true);
    }
    
    /**
     * @param string $path
     * @param bool $cleanup = false
     * @throws \Exception
     */
    public function installModule($moduleDirPath, $cleanup = false)
    {
        if (!file_exists($moduleDirPath)) {
            throw new \Exception(__METHOD__ . ': source path not exists');
        }
        
        $moduleName = $this->getModuleName(
            $moduleDirPath, 
            $this->config->moduleStruct['sql']
        );
        
        if ($this->isInstallingModuleExists($moduleName)) {
            if ($cleanup) {
                $this->rrmdir($moduleDirPath);
            }
            throw new \Exception(__METHOD__ . ': installing module or module with the same name already exists');
        }
        
        $this->copyModuleSrc(
            $moduleDirPath,
            $this->root,
            $moduleName,
            $this->config->moduleStruct['dir']
        );
        
        $this->execDbQueries(
            $this->getModuleSqlFile(
                $moduleDirPath, 
                $this->config->moduleStruct['sql']
            )
        );
        
        if ($cleanup) {
            $this->rrmdir($moduleDirPath);
        }
    }
    
    /**
     * @param string $sqlFile
     * @throws \Exception
     */
    public function execDbQueries($sqlFile)
    {
        $content = @file_get_contents($sqlFile);
        if (false === $content) {
            throw new \Exception(__METHOD__ . ' file_get_contents failed');
        }
        
        $sqls = explode(';', str_replace(self::SQL_TABLE_PREFIX, C_DB_TABLE_PREFIX, $content));
        foreach ($sqls as $sql) {
            if ('' != $sql = trim($sql)) {
                if (!$this->db->query($sql)) {
                    throw new \Exception(__METHOD__ . ' SQL execution failed: ' . $this->db->getError());
                }
            }
        }
    }
    
    /**
     * Remove all temp items.
     */
    public function cleanup()
    {
        if (file_exists($this->tempFile)) {
            @unlink($this->tempFile);
        }
        
        if (file_exists($this->tempDir)) {
            $this->rrmdir($this->tempDir);
        }
    }
    
    /**
     * @param string $url
     * @param string $localPath
     * @throws \Exception
     */
    protected function saveRemoteFile($url, $localPath)
    {
        if (!@copy($url, $localPath)) {
            throw new \Exception(__METHOD__ . ' failed');
        }
    }
    
    /**
     * @param string $archive
     * @param string $extractPath
     * @throws \Exception
     */
    protected function extractArchive($archive, $extractPath)
    {
        $zip = new \ZipArchive();
        if ($zip->open($archive) === true) {
            $zip->extractTo($extractPath);
            $zip->close();
        } else {
            throw new \Exception(__METHOD__ . ' failed');
        }
    }
    
    /**
     * @param string $srcPath
     * @param string $moduleFile
     * @return string
     * @throws \Exception
     */
    protected function getModuleName($srcPath, $moduleFile)
    {
        foreach (glob($srcPath . $moduleFile) as $filename) {
            return pathinfo($filename, PATHINFO_FILENAME);
        }
        throw new \Exception(__METHOD__ . ' failed');
    }
    
    /**
     * @param string $srcPath
     * @param string $moduleFile
     * @return string
     * @throws \Exception
     */
    protected function getModuleSqlFile($srcPath, $moduleFile)
    {
        foreach (glob($srcPath . $moduleFile) as $filename) {
            return $filename;
        }
        throw new \Exception(__METHOD__ . ' failed');
    }
    
    /**
     * @param string $srcPath
     * @param string $dstPath
     * @param string $moduleName
     * @param array $struct
     * @throws \Exception
     */
    protected function copyModuleSrc($srcPath, $dstPath, $moduleName, $struct)
    {
        $arr = [];
        foreach ($struct as $dir) {
            if ($dir == strtolower($dir)) {
                $pathFrom = $srcPath . $dir . $moduleName;
                $pathTo = $dstPath . $dir . $moduleName;
            } else {
                $pathFrom = $srcPath . $dir . ucfirst($moduleName);
                $pathTo = $dstPath . $dir . ucfirst($moduleName);
            }
            
            if (!file_exists($pathFrom)) {
                continue;
            }
            
            if (file_exists($pathTo)) {
                throw new \Exception(__METHOD__ . ': destination path already exists');
            }
            
            $arr[] = ['src' => $pathFrom, 'dst' => $pathTo];
        }
        
        if (0 == count($arr)) {
            throw new \Exception(__METHOD__ . ': nothing to install');
        }
        
        foreach ($arr as $a) {
            $this->xcopy($a['src'], $a['dst']);
        }
    }
    
    /**
     * @param string $src
     * @param string $dst
     * @throws \Exception
     */
    protected function xcopy($src, $dst) {
        $files = @scandir($src);
        if (!$files) {
            throw new \Exception(__METHOD__ . ': scandir failed');
        }
        if (!file_exists($dst)) {
            if (!@mkdir($dst, $this->config->installedDirMode, true)) {
                throw new \Exception(__METHOD__ . ': mkdir failed');
            }
        }
        
        foreach ($files as $file) {
            if ('.' == $file || '..' == $file) {
                continue;
            }
            $srcItm = $src . '/' . $file;
            $dstItm = $dst . '/' . $file;
            if (is_dir($srcItm)) {
                if (!@mkdir($dstItm, $this->config->installedDirMode, true)) {
                    throw new \Exception(__METHOD__ . ': mkdir failed');
                }
                $this->xcopy($srcItm, $dstItm);
            } else {
                if (!@copy($srcItm, $dstItm)) {
                    throw new \Exception(__METHOD__ . ': copy failed');
                }
            }
        }
    }
    
    /**
     * Cleanup method, hide all errors
     * @param string $dir
     */
    protected function rrmdir($dir)
    {
        if (!file_exists($dir)) {
            return;
        }
        $dh = @opendir($dir);
        if (!$dh) {
            return;
        }
        while (false !== ($file = @readdir($dh))) {
            if ('.' == $file || '..' == $file) {
                continue;
            }
            $path = $dir . '/' . $file;
            if (is_dir($path)) {
                $this->rrmdir($path);
            } else {
                @unlink($path);
            }
        }
        @closedir($dh);
        @rmdir($dir);
    }    
}
