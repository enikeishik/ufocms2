<?php
/**
 * @copyright
 */

namespace Ufocms\Modules\News;

/**
 * Main module model
 */
class Insertion extends \Ufocms\Modules\Insertion //implements IInsertion
{
    use Tools;
    
    /**
     * @param int $outputCount
     * @param string $sqlWhere = ''
     * @return int
     */
    protected function getRandomByCount($outputCount, $sqlWhere = '')
    {
        $sql =  'SELECT COUNT(*) AS Cnt' . 
                ' FROM ' . C_DB_TABLE_PREFIX . 'news' . 
                $sqlWhere;
        $cnt = $this->db->getValue($sql, 'Cnt');
        return mt_rand(0, $cnt - 1 - $outputCount);
    }
    
    protected function getItems()
    {
        $sql =  'SELECT IconAttributes' . 
                ' FROM ' . C_DB_TABLE_PREFIX . 'news_sections' . 
                ' WHERE SectionId=' . $this->data['SourceId'];
        $item = $this->db->getItem($sql);
        $iconAttributes = $item['IconAttributes'];
        
        $sqlWhere = ' WHERE SectionId=' . $this->data['SourceId'] . 
                    ' AND IsHidden=0' . 
                    ' AND DateCreate<=NOW()';
        $sql =  'SELECT Id,DateCreate,Title,Author,Icon,Announce,Body' . 
                ' FROM ' . C_DB_TABLE_PREFIX . 'news' . 
                $sqlWhere;
        
        //если установлен параметр ItemsIds, выводим только запрошенные элементы
        if (0 < strlen($this->data['ItemsIds'])) {
            if ($this->data['ItemsIds'] == (int) $this->data['ItemsIds']) {
                $sql .= ' AND t1.Id=' . $this->data['ItemsIds'];
            } else if ($this->tools->isStringOfIntegers($this->data['ItemsIds'])) {
                $sql .= ' AND t1.Id IN (' . $this->data['ItemsIds'] . ')';
                //если количество равно нулю, но указаны конкретные идентификаторы, 
                //выводим в порядке следования идентификаторов
                if (0 == $this->data['ItemsCount']) {
                    $sql .= ' ORDER BY FIELD(Id, ' . $this->data['ItemsIds'] . ')';
                }
            }
        }
        
        //сотрировка по умолчанию
        $sqlSort = ' ORDER BY DateCreate DESC';
        //если количество больше нуля, смещение неотрицательно, 
        //выводим указанное количество с указанного смещения 
        //с заданной сортировкой
        if (0 < $this->data['ItemsCount'] && 0 <= $this->data['ItemsStart']) {
            $sql .= $sqlSort . ' LIMIT ' . $this->data['ItemsStart'] . ', ' . $this->data['ItemsCount'];
        //если количество больше нуля, смещение отрицательно, 
        //выводим указанное количество в случайном порядке
        } else if (0 < $this->data['ItemsCount']) {
            //$sql .= ' ORDER BY RAND()' . 
            //        ' LIMIT ' . $this->data['ItemsCount'];
            $sql .= ' LIMIT ' . $this->getRandomByCount($this->data['ItemsCount'], $sqlWhere) . 
                    ', ' . $this->data['ItemsCount'];
        //если количество отрицательно, а смещение неотрицательно, 
        //выводим с указанного смещения все записи
        //с заданной сортировкой
        } else if (0 > $this->data['ItemsCount'] && 0 <= $this->data['ItemsStart']) {
            $sql .= $sqlSort . ' LIMIT ' . $this->data['ItemsStart'] . ', 999999999';
        //если оба параметра отрицательные
        //выводим все записи в случайном порядке
        //ВНИМАНИЕ! этот вариант следует использовать 
        //только для небольшого количества элементов в источнике
        } else if (0 > $this->data['ItemsCount']) {
            $sql .= ' ORDER BY RAND()';
        }
        //если количество равно нулю, но указаны конкретные идентификаторы, 
        //выводим в порядке следования идентификаторов, см. выше
        
        //получаем данные раздела-источника
        $items = $this->db->getItems($sql);
        if (null !== $items) {
            $itemsCount = count($items);
            $i = 0;
            foreach ($items as &$item) {
                $item = array_merge(
                    $item, 
                    array(
                        'ItemsOutput'           => $itemsCount, 
                        'IconAttributes'        => $iconAttributes, 
                        'InsertionItemNumber'   => ++$i, 
                    )
                );
            }
        }
        return $items;
    }
}
