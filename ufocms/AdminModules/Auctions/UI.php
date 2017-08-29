<?php
/**
 * @copyright
 */

namespace Ufocms\AdminModules\Auctions;

/**
 * Module level UI
 */
class UI extends \Ufocms\AdminModules\UI
{
    /**
     * @see parent
     */
    protected function listItemsHeaderField(array $field)
    {
        if ('IsClosed' == $field['Name']) {
            return '';
        }
        return parent::listItemsHeaderField($field);
    }
    
    /**
     * @see parent
     */
    protected function listItemsItemField(array $field, array $item)
    {
        if ('IsClosed' == $field['Name']) {
            return '';
        }
        return parent::listItemsItemField($field, $item);
    }
    
    /**
     * @see parent
     */
    protected function listItemsItem(array $fields, array $item)
    {
        $colspan = count($item) - 2;
        $s = parent::listItemsItem($fields, $item);
        $s .=   '<tr><td colspan="' . $colspan . '" style="padding: 0px;"></td></tr>' . 
                '<tr' . ((array_key_exists('disabled', $item) && $item['disabled']) ? ' class="disabled"' : '') . '>' . 
                '<td colspan="' . $colspan . '" style="border-top: dashed #999 1px; border-bottom: solid #999 1px; padding-bottom: 10px;">';
        if ($item['IsClosed']) {
            $s .=   'Аукцион завершен';
            if ($item['PriceStart'] != $item['PriceCurrent']) {
                $result = $this->model->getAuctionResults($item['Id']);
                $user = $this->core->getUsers()->get($result['LastUserId']);
                $s .=   ', ставок ' . $result['Cnt'] . 
                        ', победитель <a href="' . $this->config->rootUrl . '/users/' . $user['Id'] . '" target="_blank">' . $user['Title'] . '</a>';
            } else {
                $s .= ', ставок не было';
            }
        } else if (strtotime($item['DateStart']) <= time()) {
            $s .=   '<b>Аукцион открыт</b>';
        } else {
            $s .=   'Аукцион ожидает открытия';
        }
        $s .=   '</td></tr>';
        return $s;
    }
    
    /**
     * @see parent
     */
    protected function setMainTabs()
    {
        parent::setMainTabs();
        $tab = '<a href="?' . $this->config->paramsNames['sectionId'] . '=' . $this->params->sectionId . '&' . $this->config->paramsNames['subModule'] . '=settings"' . ('settings' == $this->params->subModule ? ' class="current"' : '') . ' title="Настройки модуля">настройки</a>';
        $this->appendMainTab('Settings', $tab, 'Items');
        $tab = '<a href="?' . $this->config->paramsNames['sectionId'] . '=' . $this->params->sectionId . '"' . (is_null($this->params->subModule) ? ' class="current"' : '') . ' title="содержимое раздела">содержимое</a>';
        $this->appendMainTab('Items', $tab);
    }
}
