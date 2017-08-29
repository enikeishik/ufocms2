<?php
function odt2text($filename)
{
    return getTextFromZippedXML($filename, 'content.xml');
}
function docx2text($filename)
{
    return getTextFromZippedXML($filename, 'word/document.xml');
}
function getTextFromZippedXML($archiveFile, $contentFile)
{
    $zip = new ZipArchive;
    if ($zip->open($archiveFile)) {
        if (($index = $zip->locateName($contentFile)) !== false) {
            $content = $zip->getFromIndex($index);
            $zip->close();
            
            $xml = DOMDocument::loadXML($content, LIBXML_NOENT | LIBXML_XINCLUDE | LIBXML_NOERROR | LIBXML_NOWARNING);
            return trim(strip_tags(str_replace('</w:p>', "\r\n", $xml->saveXML())));
        } else {
            $zip->close();
        }
    }
    return '';
}
?>