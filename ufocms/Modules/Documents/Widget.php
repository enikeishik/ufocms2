<?php
/**
 * @copyright
 */

namespace Ufocms\Modules\Documents;

/**
 * Widget class
 */
class Widget extends \Ufocms\Modules\Widget
{
    /**
     * @see parent
     */
    protected function setContext()
    {
        parent::setContext();
        
        $items = array();
        if (is_array($this->params)) {
            $items = $this->getContent(
                $this->srcSections,
                $this->params['WordsCount'],
                $this->params['StartMark'],
                $this->params['StopMark']
            );
        }
        
        $this->context = array_merge(
            $this->context, 
            array(
                'items' => $items
            )
        );
    }
    
    /**
     * @param string $srcSections
     * @param int $wordsCount
     * @param string $startMark
     * @param string $stopMark
     * @return array
     */
    protected function getContent($srcSections, $wordsCount, $startMark, $stopMark)
    {
        $sql =  'SELECT d.body, s.indic' . 
                ' FROM ' . C_DB_TABLE_PREFIX . 'documents AS d' . 
                ' INNER JOIN sections AS s ON d.SectionId=s.id' . 
                ' WHERE d.SectionId IN (' . $srcSections . ')';
        $items = $this->db->getItems($sql);
        if (null === $items || 0 == count($items)) {
            return array();
        }
        if (0 == $wordsCount && '' == $startMark && '' == $stopMark) {
            return $items;
        }
        foreach ($items as &$item) {
            $item['body'] = strip_tags($item['body']);
            if ('' == $item['body']) {
                continue;
            }
            
            if (0 < $wordsCount) {
                $arr = preg_split('/[\s,]+/', $item['body'], $wordsCount + 1, PREG_SPLIT_NO_EMPTY);
                unset($arr[$wordsCount]);
                $item['body'] = implode(' ', $arr);
                
            } else if ('' != $startMark && '' != $stopMark) {
                $posStart = strpos($item['body'], $startMark);
                $posStop = strpos($item['body'], $stopMark);
                if (false === $posStart) {
                    $posStart = 0;
                } else {
                    $posStart = $posStart + strlen($startMark);
                }
                if (false === $posStop) {
                    $posStop = strlen($item['body']);
                }
                if ($posStart < $posStop) {
                    $item['body'] = substr($item['body'], $posStart, $posStop - $posStart);
                } else {
                    $item['body'] = '';
                }
                
            } else if ('' != $startMark) {
                $pos = strpos($item['body'], $startMark);
                if (false !== $pos) {
                    $item['body'] = substr($item['body'], $pos + strlen($startMark));
                }
                
            } else if ('' != $stopMark) {
                $pos = strpos($item['body'], $stopMark);
                if (false !== $pos) {
                    $item['body'] = substr($item['body'], 0, $pos);
                }
                
            }
        }
        unset($item);
        return $items;
    }
}
