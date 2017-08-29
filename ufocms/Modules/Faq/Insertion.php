<?php
/**
 * @copyright
 */

namespace Ufocms\Modules\Faq;

/**
 * Main module model
 */
class Insertion extends \Ufocms\Modules\Insertion //implements IInsertion
{
    /**
     * @param int $outputCount
     * @param string $sqlWhere = ''
     * @return int
     */
    protected function getRandomByCount($outputCount, $sqlWhere = '')
    {
        $sql =  'SELECT COUNT(*) AS Cnt' . 
                ' FROM ' . C_DB_TABLE_PREFIX . 'faq' . 
                $sqlWhere;
        $cnt = $this->db->getValue($sql, 'Cnt');
        return mt_rand(0, $cnt - 1 - $outputCount);
    }
    
    protected function getItems()
    {
        $sql =  'SELECT Orderby' . 
                ' FROM ' . C_DB_TABLE_PREFIX . 'faq_sections' . 
                ' WHERE SectionId=' . $this->data['SourceId'];
        $orderBy = $this->db->getValue($sql, 'Orderby');
        
        //получаем данные раздела-источника
        $sqlWhere = ' WHERE SectionId=' . $this->data['SourceId'] . 
                    ' AND IsHidden=0';
        $sql =  'SELECT Id,DateCreate,USign,UEmail,UUrl,UMessage,DateAnswer' . 
                ' FROM ' . C_DB_TABLE_PREFIX . 'faq' . 
                 $sqlWhere;
        //если оба параметра вставки (смещение, количество) неотрицательны, 
        //выводим указанное количество с указанного смещения 
        //по сортировке раздела-источника
        if (0 <= $this->data['ItemsStart'] && 0 <= $this->data['ItemsCount']) {
            if ($orderBy) {
                $sql .= ' ORDER BY DateCreate';
            } else {
                $sql .= ' ORDER BY DateCreate DESC';
            }
            $sql .= ' LIMIT ' . $this->data['ItemsStart'] . ", " . $this->data['ItemsCount'];
        //если первый параметр отрицательный, а второй нет
        //выводим указанное количество в случайном порядке
        } else if (0 <= $this->data['ItemsCount']) {
            //$sql .= ' ORDER BY RAND()' . 
            //        ' LIMIT ' . $this->data['ItemsCount'];
            $sql .= ' LIMIT ' . $this->getRandomByCount($this->data['ItemsCount'], $sqlWhere) . 
                    ', ' . $this->data['ItemsCount'];
        //если первый параметр неотрицательный, а второй отрицательный
        //выводим с указанного смещения все записи
        //по сортировке раздела-источника
        } else if (0 <= $this->data['ItemsStart']) {
            if ($orderBy) {
                $sql .= ' ORDER BY DateCreate';
            } else {
                $sql .= ' ORDER BY DateCreate DESC';
            }
            $sql .= ' LIMIT ' . $this->data['ItemsStart'] . ', 999999999';
        //если оба параметра отрицательные
        //выводим все записи в случайном порядке
        //ВНИМАНИЕ! этот вариант следует использовать 
        //только для небольшого количества элементов в источнике
        } else {
            $sql .= ' ORDER BY RAND()';
        }
        
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
                        'InsertionItemNumber'   => ++$i, 
                    )
                );
            }
        }
        return $items;
    }
}
