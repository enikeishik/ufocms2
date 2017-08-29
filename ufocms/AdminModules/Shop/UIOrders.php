<?php
/**
 * @copyright
 */

namespace Ufocms\AdminModules\Shop;

/**
 * Module level UI
 */
class UIOrders extends \Ufocms\AdminModules\Shop\UI
{
    /**
     * @see parent
     */
    protected function getItemFuncHtml(array $item, $func)
    {
        if ('status' == $func) {
            return ' <a href="' . $this->basePath . '&' . $this->config->paramsNames['action'] . '=status&' . $this->config->paramsNames['itemId'] . '=' . $item['itemid'] . '" title="Изменить статус">Статус</a>';
        } else {
            return ' <a href="' . $this->basePath . '&' . $this->config->paramsNames['action'] . '=edit&' . $this->config->paramsNames['itemId'] . '=' . $item['itemid'] . '" title="Редактировать">Редакт.</a>';
        }
    }
    
    /**
     * @see parent
     */
    public function singleItems()
    {
        $fields = $this->model->getFields();
        $items = $this->model->getItems();
        $path = $this->core->getSection($this->params->sectionId, 'path')['path'];
        
        $s =    '<style type="text/css">' . 
                '.itemhead table { border-bottom: solid #999 1px; border-collapse: collapse; width: 100%; } ' . 
                '.itemhead th { border-right: solid #999 1px; border-bottom: solid #999 1px; background-color: #ccc; padding: 2px; font-weight: normal; }' . 
                '.itemhead th:last-child { border-right-width: 0px; }' . 
                '.itemhead td { border-right: solid #999 1px; padding: 2px; background-color: #eee; text-align: center; }' . 
                '.itemhead td:last-child { border-right-width: 0px; }' . 
                '.itembody table { border-collapse: collapse; width: 100%; } ' . 
                '.itembody table tr:nth-child(2n) { background-color: #f0f0f8; } ' . 
                '.itembody th { border-right: solid #999 1px; border-bottom: solid #999 1px; background-color: #eee; padding: 2px; font-weight: normal; }' . 
                '.itembody td { border-right: solid #999 1px; padding: 2px; }' . 
                '.itemhead td.status2 { background-color: #dfd; }' . 
                '.itemhead td.status3 { background-color: #dff; }' . 
                '.itemhead td.status4 { background-color: #fdd; }' . 
                '.itemhead td.status9 { background-color: #a98; }' . 
                '</style>' . 
                '<div class="items">';
        foreach ($items as $item) {
            $disabled = (array_key_exists('disabled', $item) && $item['disabled']) ? ' disabled' : '';
            $s .=   '<div class="item' . $disabled . '">';
            
            $s .=   '<div class="itemhead">' . 
                        '<table>' . 
                        '<tr>' . 
                            '<th>id</th>' . 
                            '<th>Статус</th>' . 
                            '<th>Инициирован</th>' . 
                            '<th>Оформлен</th>' . 
                            '<th>Оплачен</th>' . 
                            '<th>Собран</th>' . 
                            '<th>Отправлен</th>' . 
                            '<th>Закрыт</th>' . 
                        '</tr>' . 
                        '<tr>' . 
                            '<td>' . $item['Id'] . '</td>' . 
                            '<td class="status' . $item['Status'] . '">' . htmlspecialchars($this->model->getStatus($item['Status'])['Title']) . '</td>' . 
                            '<td>' . $item['DateInit'] . '</td>' . 
                            '<td>' . ('0000-00-00 00:00:00' != $item['DateCreate'] ? $item['DateCreate'] : '') . '</td>' . 
                            '<td>' . ('0000-00-00 00:00:00' != $item['DatePaid'] ? $item['DatePaid'] : '') . '</td>' . 
                            '<td>' . ('0000-00-00 00:00:00' != $item['DateEquip'] ? $item['DateEquip'] : '') . '</td>' . 
                            '<td>' . ('0000-00-00 00:00:00' != $item['DateSend'] ? $item['DateSend'] : '') . '</td>' . 
                            '<td>' . ('0000-00-00 00:00:00' != $item['DateClosed'] ? $item['DateClosed'] : '') . '</td>' . 
                        '</tr>' . 
                        '</table>' . 
                    '</div>';
            $user = '';
            if (0 != $item['UserId']) {
                $userData = $this->core->getUsers()->get($item['UserId']);
                $user = '<div class="itemhead">' . 
                            '<div class="itemfield type-text">' . 
                                '<span class="fieldname">Пользователь</span>' . 
                                '<span class="fieldvalue">' . 
                                    '<a href="/users/' . $item['UserId'] . '" target="_blank">' . htmlspecialchars($userData['Title']) . '</a>' . 
                                '</span>' . 
                            '</div>' . 
                        '</div>';
                unset($userData);
            }
            $s .=   '<div class="itemhead">' . 
                        '<div class="funcs">' . $this->getItemFuncsHtml($item, array('edit', 'status')) . '</div>' . 
                        $user . 
                        '<div class="itemfield type-text">' . 
                            '<span class="fieldname">Телефон</span>' . 
                            '<span class="fieldvalue">' . htmlspecialchars($item['Phone']) . '</span>' . 
                        '</div>' . 
                        '<div class="itemfield type-text">' . 
                            '<span class="fieldname">Email</span>' . 
                            '<span class="fieldvalue">' . htmlspecialchars($item['Email']) . '</span>' . 
                        '</div>' . 
                        '<div class="itemfield type-text">' . 
                            '<span class="fieldname">Адрес</span>' . 
                            '<span class="fieldvalue">' . htmlspecialchars($item['Address']) . '</span>' . 
                        '</div>' . 
                    '</div>';
            $s .=   '<div class="itemhead">' . 
                        '<div class="itemfield type-bigtext">' . 
                            '<span class="fieldname">Комментарий</span>' . 
                            '<span class="fieldvalue">' . htmlspecialchars($item['Comment']) . '</span>' . 
                        '</div>' . 
                    '</div>';
            
            $orderItems = $this->model->getOrderItems($item['Id']);
            if (0 < count($orderItems)) {
                $s .=   '<div class="itembody">' . 
                        '<table>' . 
                        '<tr>' . 
                        '<th>Категория</th>' . 
                        '<th>Товар</th>' . 
                        '<th width="80" style="text-align: right;">Кол-во</th>' . 
                        '</tr>';
                foreach ($orderItems as $oi) {
                    $s .=   '<tr>' . 
                            '<td>' . ($oi['CategoryTitle'] ? '<a href="' . $path . $oi['CategoryAlias'] . '/" target="_blank">' . $oi['CategoryTitle'] . '</a>' : 'товар удален') . '</td>' . 
                            '<td>' . ($oi['Title'] ? '<a href="' . $path . $oi['CategoryAlias'] . '/' . $oi['Alias'] . '/" target="_blank">' . $oi['Title'] . '</a>' : 'товар удален') . '</td>' . 
                            '<td align="right">' . $oi['ItemsCount'] . '</td>' . 
                            '</tr>';
                }
                $s .=   '</table>' . 
                        '</div>';
            } else {
                $s .=   '<div class="itembody" style="text-align: center;">нет товаров</div>';
            }
            
            $s .=   '<div class="itemfoot">' . 
                        '<div class="itemfield type-bigtext">' . 
                            '<span class="fieldname">Отчет<span class="info" title="видно пользователю в личном кабинете">i</span></span>' . 
                            '<span class="fieldvalue">' . htmlspecialchars(strip_tags($item['Report'])) . '</span>' . 
                        '</div>' . 
                    '</div>';
            $s .=   '<div class="itemfoot">' . 
                        '<div class="itemfield type-mediumtext">' . 
                            '<span class="fieldname">Заметки<span class="info" title="видно только администратору">i</span></span>' . 
                            '<span class="fieldvalue">' . htmlspecialchars($item['Notes']) . '</span>' . 
                        '</div>' . 
                    '</div>';
            
            $s .= '</div>';
        }
        return $s . '</div>';
    }
    
    /**
     * @see parent
     */
    protected function formFieldMediumtextElement(array $field, $value)
    {
        return  '<textarea class="mceNoEditor" cols="50" rows="10"' . $this->getFormFieldAttributes($field, $value) . '>' . 
                str_replace('<br>', "\r\n", str_replace(["\r", "\n"], '', $value)) . 
                '</textarea>';
    }
    
    /**
     * @see parent
     */
    protected function formHandler(array $item = null)
    {
        if ('edit' == $this->params->action) {
            return parent::formHandler($item);
        }
        
        return  $this->basePath . 
                '&' . $this->config->paramsNames['action'] . '=setstatus' . 
                (null !== $item ? '&' . $this->config->paramsNames['itemId'] . '=' . $item['itemid'] : '');
    }
    
    /**
     * @see parent
     */
    public function form()
    {
        if ('edit' == $this->params->action) {
            return parent::form();
        }
        
        //status change form
        $item = $this->model->getItem();
        if (!isset($item['itemid'])) {
            $item['itemid'] = 0;
        }
        
        $s = $this->formBegin($this->formElementAttributes($this->formHandler($item)));
        
        $currentStatus = $this->model->getStatus($item['Status']);
        $nextStatus = $this->model->getNextStatus($item['Status']);
        $s .=   '<tr>' . 
                '<td><label>Статус</label></td>' . 
                '<td align="center">текущий: ' . $currentStatus['Title'] . ' (' . $item['Status'] . ')</td>' . 
                '<td align="center"><code>-&gt;</code></td>';
        if (!is_null($nextStatus) && $nextStatus['Data']['Admin']) {
            $s .=   '<td align="center">установить: ' . $nextStatus['Data']['Title'] . ' (' . $nextStatus['Status'] . ')' . 
                    '<input type="hidden" name="Status" value="' . $nextStatus['Status'] . '"></td>';
        } else {
            $s .=   '<td>установить новый статус невозможно</td>';
        }
        $s .=   '</tr>';
        
        if (!is_null($nextStatus) && $nextStatus['Data']['Admin']) {
            $s .=   '<tr><td colspan="4" align="center">' . $this->formSubmitElement($this->formSubmitElementAttributes()) . '</td></tr>' . 
                    '</table></form>';
        } else {
            $s .=   '<tr><td colspan="4" align="center"><input type="button" value="Вернуться" onclick="history.back()"></td></tr>' . 
                    '</table></form>';
        }
        
        return $s;
    }
}
