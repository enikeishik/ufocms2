<?php
/**
 * @copyright
 */

namespace Ufocms\Modules\SysSendform;

/**
 * Main module model
 */
class Model extends \Ufocms\Modules\Model //implements IModel
{
    /**
     * Form fields with values
     * @var array<field => value>
     */
    protected $formData = null;
    
    /**
     * DB id value for form data record
     * @var int
     */
    protected $formId = null;
    
    public function init()
    {
        $this->getSettings();
        
        if (!$this->check()) {
            return;
        }
        
        $data = $this->getData();
        $this->formData = $data['form'];
        
        $formDataFormatted = '';
        $formatterTemplate = $this->getFormatterTemplate();
        if (null !== $formatterTemplate) {
            ob_start();
            foreach ($data['form'] as $field => $value) {
                $this->formatter($field, $value, $formatterTemplate);
            }
            $formDataFormatted = ob_get_clean();
        } else {
            $formDataFormatted = print_r($data['form'], true);
        }
        
        $this->actionResult['db'] = $this->save($formDataFormatted);
        
        $to = array();
        $site = $this->core->getSite();
        if (array_key_exists($this->settings['mailToParam'], $site)) {
            $arr = explode(',', $site[$this->settings['mailToParam']]);
            foreach ($arr as $email) {
                $email = trim($email);
                if ($this->tools->isEmail($email)) {
                    $to[] = $email;
                }
            }
        }
        $to = array_merge($to, $data['emails']);
        if (0 == count($to)) {
            return;
        }
        $to = implode(', ', $to);
        
        //TODO: make FROM header if needed
        
        $marks = array(
            $this->settings['markIp'], 
            $this->settings['markReferer'], 
            $this->settings['markForm']
        );
        $values = array(
            isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : 'IP not defined', 
            isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'referer not defined', 
            $formDataFormatted
        );
        
        if (array_key_exists($this->settings['mailSubjectParam'], $site)) {
            $subject = str_replace($marks, $values, $site[$this->settings['mailSubjectParam']]);
        } else {
            $subject = str_replace($marks, $values, $this->settings['mailSubjectDefault']);
        }
        
        if (array_key_exists($this->settings['mailBodyParam'], $site)) {
            $message = str_replace($marks, $values, $site[$this->settings['mailBodyParam']]);
        } else {
            $message = str_replace($marks, $values, $this->settings['mailBodyDefault']);
        }
        
        $this->actionResult['email'] = $this->tools->getMessenger($this->config)->sendEmail(
            $to, 
            $subject, 
            $message, 
            $data['files']
        );
        
        if ($this->actionResult['email']) {
            $this->save();
        }
    }
    
    /**
     * @todo move it to separate config class/file
     */
    public function getSettings()
    {
        if (null !== $this->settings) {
            return $this->settings;
        }
        //TODO: use site params
        $this->settings = array(
            'captcha'               => false, 
            'maxFileSize'           => $this->config->uploadFileMaxSize, 
            'ignoreFieldMark'       => '!', 
            'mailToParam'           => 'SiteEMail', 
            'mailFromParam'         => 'SiteEMailFrom', 
            'mailSubjectParam'      => 'SendformSubj', 
            'mailSubjectDefault'    => 'Form was sended from page {REFERER}', 
            'mailBodyParam'         => 'SendformBody', 
            'mailBodyDefault'       => 'Form was sended from page {REFERER}<br />Sender IP {IP}<hr />{FORM}', 
            'markForm'              => '{FORM}', 
            'markReferer'           => '{REFERER}', 
            'markIp'                => '{IP}', 
            'formatterTemplate'     => 'formatter.php', 
            'uploadPath'            => '/.sendform', 
            'uploadFileMode'        => $this->config->staticFileMode, 
        );
        return $this->settings;
    }
    
    public function getItems()
    {
        return $this->formData;
    }
    
    public function getItem()
    {
        $sql =  'SELECT *' . 
                ' FROM ' . C_DB_TABLE_PREFIX . 'sendforms' . 
                ' WHERE Id=' . $this->formId;
        return $this->db->getItem($sql);
    }
    
    /**
     * @return string|null
     */
    protected function getFormatterTemplate()
    {
        if (defined('C_THEME') && '' != C_THEME) {
            $template = $this->config->rootPath . 
                        $this->config->templatesDir . 
                        '/' . C_THEME;
        } else {
            $template = $this->config->rootPath . 
                        $this->config->templatesDir . 
                        $this->config->themeDefault;
        }
        $template .=    '/' . strtolower($this->module['Name']) . 
                        '/' . $this->settings['formatterTemplate'];
        if (file_exists($template)) {
            // /templates/mytemplate/mymodule/entry
            return $template;
        } else {
            // /templates/default/mymodule/entry
            $template = $this->config->rootPath . 
                        $this->config->templatesDir . $this->config->themeDefault . 
                        '/' . strtolower($this->module['Name']) . 
                        '/' . $this->settings['formatterTemplate'];
            if (file_exists($template)) {
                return $template;
            } else {
                // /templates/default/default/entry
                $template = $this->config->rootPath . 
                            $this->config->templatesDir . $this->config->themeDefault . 
                            $this->config->templateDefault . 
                            '/' . $this->settings['formatterTemplate'];
                if (file_exists($template)) {
                    return $template;
                } else {
                    return null;
                }
            }
        }
    }
    
    /**
     * @param string $field
     * @param string $value
     * @param string $formatterTemplate
     */
    protected function formatter($field, $value, $formatterTemplate)
    {
        extract(array('settings' => $this->settings));
        include $formatterTemplate;
    }
    
    /**
     * @return bool
     */
    protected function check()
    {
        $this->actionResult = array(
            'source'    => false, 
            'method'    => false, 
            'data'      => false, 
            'human'     => false, 
            'db'        => false, 
            'email'     => false, 
        );
        
        //проверяем достоверность источника
        if (!isset($_SERVER['HTTP_REFERER']) || !isset($_SERVER['HTTP_HOST'])) {
            return false;
        }
        if (false === strpos($_SERVER['HTTP_REFERER'], $_SERVER['HTTP_HOST'])) {
            return false;
        }
        $this->actionResult['source'] = true;
        
        if (!isset($_SERVER['REQUEST_METHOD']) || 0 != strcasecmp('POST', $_SERVER['REQUEST_METHOD'])) {
            return false;
        }
        $this->actionResult['method'] = true;
        
        //проверяем наличие входных данных
        if (0 == count($_POST)) {
            return false;
        }
        $this->actionResult['data'] = true;
        
        //проверяем что отправил форму человек
        if ($this->settings['captcha']) {
            if (!$this->tools->getCaptcha()->check()) {
                return false;
            }
        }
        $this->actionResult['human'] = true;
        return true;
    }
    
    /**
     * @return array<array 'emails', array 'form'>
     */
    protected function getData()
    {
        $data = array(
            'emails' => array(), 
            'form' => array(), 
            'files' => null
        );
        
        //собираем данные формы
        foreach ($_POST as $key => $value) {
            //игнорируем служебные поля и поля со специальной меткой
            if ('MAX_FILE_SIZE' != strtoupper($key) 
            && 0 !== stripos($key, $this->settings['ignoreFieldMark'])) {
                $data['form'][$key] = htmlspecialchars($value);
            } else {
                //пытаемся определить, не указывает ли служебное поле на дополнительный email для отсылки
                //поле содержит имя ключа, по которому ищется значение email в общих параметрах сайта
                $clearKey = substr($key, strlen($this->settings['ignoreFieldMark']));
                $siteParams = $this->core->getSite();
                if (array_key_exists($clearKey, $siteParams)) {
                    //если ключ есть, дополнительно проверяем его значение на соответствие email
                    if ($this->tools->isEmail($siteParams[$clearKey])) {
                        $data['emails'][] = $siteParams[$clearKey];
                    }
                }
            }
        }
        
        //собираем загруженные файлы
        if (0 < count($_FILES)) {
            $data['files'] = array();
            foreach ($_FILES as $key => $value) {
                if (0 == $_FILES[$key]['size'] && UPLOAD_ERR_NO_FILE == $_FILES[$key]['error']) {
                    continue;
                }
                $file = $_FILES[$key]['name'];
                $data['form'][$key] = $file;
                if (UPLOAD_ERR_FORM_SIZE == $_FILES[$key]['error'] 
                || $this->settings['maxFileSize'] < $_FILES[$key]['size']) {
                    $data['form'][$key] .= ' (размер файла превысил допустимый предел, файл не был загружен)';
                    continue;
                } else if (0 != $_FILES[$key]['error']) {
                    $data['form'][$key] .= ' (error: ' . $_FILES[$key]['error'] . '; size: ' . $_FILES[$key]['size'] . ')';
                    continue;
                }
                if (false === $pos = strrpos($file, '.')) {
                    $data['form'][$key] .= ' (файл не имеет расширения)';
                    continue;
                }
                $fileName = substr($file, 0, $pos);
                $fileExt = substr($file, $pos + 1);
                
                //TODO: ... ? check ext or mime type ?
                
                //собираем для сохранения, чтобы можно было просмотреть/скачать через админку
                list($msec, $sec) = explode(' ', microtime());
                $filePath = $this->settings['uploadPath'] . '/' . date('YmdHis') . '_' . ($msec * 100000000) . '.' . $fileExt;
                if (@move_uploaded_file($_FILES[$key]['tmp_name'], $this->config->rootPath . $filePath)) {
                    if (!@chmod($this->config->staticDir . $filePath, $this->settings['uploadFileMode'])) {
                        $data['form'][$key] .= ' (error while changing mode on file `' . $file . '` to `' . $this->settings['uploadFileMode'] . '`)';
                    }
                    $data['form'][$key] .= ' (' . $filePath . ')';
                    $data['files'][] = array('Name' => $file, 'Path' => $this->config->rootPath . $filePath);
                } else {
                    $data['form'][$key] .= ' (error while moving upload file `' . $file . '` into `' . $filePath . '`)';
                }
            }
        }
        
        return $data;
    }
    
    /**
     * @param string $formData = null
     * @return bool
     */
    protected function save($formDataFormatted = null)
    {
        if (null === $formDataFormatted) {
            $sql = 'UPDATE SET Status=1 WHERE Id=' . $this->formId;
            return $this->db->query($sql);
        } else {
            $sql =  'INSERT INTO ' . C_DB_TABLE_PREFIX . 'sendforms' .
                    ' (DateCreate,Status,Url,IP,Form)' . 
                    ' VALUES(' . 
                        'NOW(),' .
                        '0,' . 
                        "'" . $this->db->addEscape($_SERVER['HTTP_REFERER']) . "'," . 
                        "'" . $this->db->addEscape($_SERVER['REMOTE_ADDR']) . "'," . 
                        "'" . $this->db->addEscape($formDataFormatted) . "'" . 
                    ')';
            if ($this->db->query($sql)) {
                $this->formId = $this->db->getLastInsertedId();
                return true;
            } else {
                return false;
            }
        }
    }
}
