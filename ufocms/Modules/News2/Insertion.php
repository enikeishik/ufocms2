<?php
/**
 * @copyright
 */

namespace Ufocms\Modules\News2;

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
                ' FROM ' . C_DB_TABLE_PREFIX . 'news2' . 
                $sqlWhere;
        $cnt = $this->db->getValue($sql, 'Cnt');
        return mt_rand(0, $cnt - 1 - $outputCount);
    }
    
    protected function getItems()
    {
        $sql =  'SELECT Orderby,IconAttributes,InsIconAttributes' . 
                ' FROM ' . C_DB_TABLE_PREFIX . 'news2_sections' . 
                ' WHERE SectionId=' . $this->data['SourceId'];
        $item = $this->db->getItem($sql);
        $iconAttributes = $item['IconAttributes'];
        $insIconAttributes = $item['InsIconAttributes'];
        $orderBy = $item['Orderby'];
        
        $sql =  '(t1.SectionId=' . $this->data['SourceId'] . 
                    ' OR t1.Id IN(' . 
                        'SELECT ItemId FROM ' . C_DB_TABLE_PREFIX . 'news2_ns WHERE AnotherSectionId=' . $this->data['SourceId'] . 
                    ')' . 
                ')';
        if ('' != $this->data['SourcesIds']) {
            if ($this->tools->isStringOfIntegers($this->data['SourcesIds'])) {
                $sql =  '(t1.SectionId IN (' . $this->data['SourceId'] . ',' . $this->data['SourcesIds'] . ')' . 
                            ' OR t1.Id IN (' . 
                                'SELECT ItemId FROM ' . C_DB_TABLE_PREFIX . 'news2_ns' . 
                                ' WHERE AnotherSectionId IN(' . $this->data['SourceId'] . ',' . $this->data['SourcesIds'] . ')' . 
                            ')' . 
                        ')';
            }
        }
        $sqlWhere = ' WHERE ' . $sql . 
                    ' AND t1.IsHidden=0 AND t2.isenabled!=0' . 
                    ' AND t1.DateCreate<=NOW()';
        $sql = 'SELECT t1.Id, t1.DateCreate, t1.DateView,' . 
               ' t1.Title, t1.Author, t1.Icon, t1.InsIcon, t1.Announce, t1.Body, t1.ViewedCnt,' . 
               ' t2.path, t2.image, t2.timage, t2.indic, t2.title' . 
               ' FROM ' . C_DB_TABLE_PREFIX . 'news2 AS t1' . 
               ' INNER JOIN ' . C_DB_TABLE_PREFIX . 'sections AS t2 ON t1.SectionId=t2.id' . 
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
        $sqlSort = ' ORDER BY t1.DateCreate DESC';
        //сортировка вставки
        $pos = strpos($this->data['ItemsOptions'], ' ORDER BY ');
        if (false !== $pos) {
            $sort = substr($this->data['ItemsOptions'], $pos);
            //проверяем корректность указанных данных
            $flag = true;
            $arr = explode(',', str_replace(' ORDER BY ', '', $sort));
            foreach ($arr as $val) {
                $av = explode(' ', trim($val));
                if ('DateCreate' != $av[0] && 'DateView' != $av[0] 
                 && 'ViewedCnt' != $av[0] && 'Title' != $av[0]) {
                    $flag = false;
                    break;
                }
                if (1 < count($av)) {
                    if ('DESC' != $av[1]) {
                        $flag = false;
                        break;
                    }
                }
            }
            unset($arr);
            if ($flag) {
                $sqlSort = $sort;
            }
        //сортировка раздела-источника
        } else if (0 === strpos($orderBy, ' ORDER BY ')) {
            $sqlSort = $orderBy;
        }
        
        //если количество больше нуля, смещение неотрицательно, 
        //выводим указанное количество с указанного смещения 
        //с заданной сортировкой
        if (0 < $this->data['ItemsCount'] && 0 <= $this->data['ItemsStart']) {
            $sql .= $sqlSort . ' LIMIT ' . $this->data['ItemsStart'] . ', ' . $this->data['ItemsCount'];
        //если количество больше нуля, смещение отрицательно, 
        //выводим указанное количество в случайном порядке
        } else if (0 < $this->data['ItemsCount']) {
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
                        'InsIconAttributes'     => $insIconAttributes, 
                        'InsertionItemNumber'   => ++$i, 
                    )
                );
            }
        }
        return $items;
    }
}
