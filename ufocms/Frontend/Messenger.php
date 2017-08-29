<?php
/**
 * @copyright
 */

namespace Ufocms\Frontend;

/**
 * Класс отправителя сообщений.
 */
class Messenger
{
    /**
     * @var Config
     */
    protected $config = null;
    
    /**
     * Конструктор.
     * @param Config &$config = null
     */
    public function __construct(&$config = null)
    {
        $this->config =& $config;
    }
    
    /**
     * @param string $to
     * @param string $subject
     * @param string $message
     * @param array<string Name, string Path> $files
     * @param string $additionalHeaders = null
     * @return bool
     * @todo select HTML/text mail format, use Config
     */
    public function sendEmail($to, $subject, $message, array $files = null, $additionalHeaders = '')
    {
        if (null === $files) {
            $headers =  'Content-type: text/html; charset=utf-8';
            $message =  '<html><head><title>' . $subject . '</title></head><body>' . 
                        $message . '</body></html>';
        } else {
            $boundary = '--1111111111111111111111--222-33333-44--';
            $headers =  'Mime-Version: 1.0' . "\n" . 
                        'Content-Type: multipart/mixed; boundary="' . $boundary . '"';
            $mimeFiles = array();
            foreach ($files as $file) {
                $handle = fopen($file['Path'], 'rb');
                $mimeFiles[] =  "\n\n--" . $boundary . "\n" . 
                                'Content-Type: application/octet-stream; name=' . $file['Name'] . "\n" . 
                                'Content-Transfer-Encoding: base64' . "\n" . 
                                'Content-Disposition: attachment; filename=' . $file['Name'] . "\n\n" . 
                                chunk_split(base64_encode(fread($handle, filesize($file['Path'])))) . "\n";
                fclose($handle);
            }
            $message = "--" . $boundary . "\n" . 
                       "Content-type: text/html; charset=utf-8\n" . 
                       "Content-Transfer-Encoding: quoted-printable\n\n" . 
                       '<html><head><title>' . $subject . '</title></head><body>' . 
                       $message . '</body></html>' . 
                       implode('', $mimeFiles) . 
                       $boundary . '--' . "\n\n";
        }
        return mail(
            $to, 
            $subject, 
            $message, 
            $headers . $additionalHeaders
        );
    }
}
