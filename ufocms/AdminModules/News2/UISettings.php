<?php
/**
 * @copyright
 */

namespace Ufocms\AdminModules\News2;

/**
 * Module level UI
 */
class UISettings extends \Ufocms\AdminModules\News2\UI
{
    protected function formField(array $field, $value)
    {
        if ('Orderby' == $field['Name']) {
            $s = '<tr class="type-text"><td><label>' . $field['Title'] . '</label></td><td>';
            $s .=   '<input type="text" maxlength="255" size="50" name="' . $field['Name'] . '" id="' . strtolower($field['Name']) . '" value="' . htmlspecialchars($value) . '" />';
            $s .=   '<br><span class="note">доступные для сортировки поля: <code>Id, UserId, DateCreate, DateView, Title, Author, ViewedCnt</code>' . 
                    '<br>пример: <code> ORDER BY Author, ViewedCnt DESC</code></span>';
            $s .= '</td></tr>';
            return $s;
        } else if ('AlertEmailBody' == $field['Name']) {
            $s = '<tr class="type-mediumtext"><td><label>' . $field['Title'] . '</label></td><td>';
            $s .=   '<textarea cols="50" rows="5" class="mceNoEditor" name="' . $field['Name'] . '" id="' . strtolower($field['Name']) . '">' . htmlspecialchars($value) . '</textarea>';
            $s .=   '<br><span class="note">{URL} — адрес новости, {DT} — дата и время создания, {IP} — адрес отправителя, {MESSAGE} — текст новости' . 
                    '<br>тэги могут использоваться в теме и тексте уведомления для отражения информации о добавленной новости</span>';
            $s .= '</td></tr>';
            return $s;
        } else {
            return parent::formField($field, $value);
        }
    }
}
