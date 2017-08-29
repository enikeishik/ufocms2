<?php
/**
 * @copyright
 */

namespace Ufocms\AdminModules\News2;

/**
 * Module level UI
 */
class UI extends \Ufocms\AdminModules\UI
{
    protected function setItemFuncs(array $item, array $funcs)
    {
        parent::setItemFuncs($item, $funcs);
        /*
        $s = '<br>';
        if () {
            $s .= ' <a href="' . $this->basePath . '&' . $this->config->paramsNames['action'] . '=rsson&' . $this->config->paramsNames['itemId'] . '=' . $item['itemid'] . '" title="Отображать в RSS ленте">RSS</a>';
        } else {
            $s .= ' <a href="' . $this->basePath . '&' . $this->config->paramsNames['action'] . '=rssoff&' . $this->config->paramsNames['itemId'] . '=' . $item['itemid'] . '" title="Скрыть из RSS ленты">RSS</a>';
        }
        $this->appendItemFunc('rss', $s);
        */
    }
    
    protected function formField(array $field, $value)
    {
        $s = parent::formField($field, $value);
        if ('SectionId' == $field['Name']) {
            $s .= '<tr class="type-text"><td><label>Другие разделы</label></td><td>';
            $s .=   '<select name="anothersections[]" size="6" multiple style="width: 99%;">' . 
                    '<option value="0">(не выбрано)</option>';
            $sections = $this->model->getAnotherSections();
            $items = $this->model->getFieldItems($field); //$field['Items']
            foreach ($items as $item) {
                if ($value == $item['Value']) {
                    continue;
                }
                if (!in_array($item['Value'], $sections)) {
                    $s .= '<option value="' . htmlspecialchars($item['Value']) . '">' . htmlspecialchars($item['Title']) . '</option>';
                } else {
                    $s .= '<option value="' . htmlspecialchars($item['Value']) . '" selected>' . htmlspecialchars($item['Title']) . '</option>';
                }
            }
            $s .=   '</select><br><span class="note">удерживайте клавишу Ctrl для выделения нескольких разделов</span>';
            $s .= '</td></tr>';
        } else if ('Body' == $field['Name']) {
            $s .= '<tr class="type-text"><td><label>Тэги</label></td><td>';
            $s .=   '<div style="float: left; width: 39%; margin-right: 10px;">' . 
                        '<textarea name="newtags" class="mceNoEditor" rows="10" cols="20" style="width: 98%; height: 94px;"></textarea><br>' . 
                        '<span class="note">вводите новые теги в поле, каждый тэг на новой строке</span>' . 
                    '</div>' . 
                    '<div style="float: left; width: 59%;">' . 
                        '<select name="tags[]" size="6" multiple style="width: 98%; height: 100px;">' . 
                        '<option value="0">(не выбрано)</option>';
            $tags = $this->model->getTags();
            $itemTags = $this->model->getItemTags();
            foreach ($tags as $tag) {
                if (!array_key_exists('Id' . $tag['Id'], $itemTags)) {
                    $s .= '<option value="' . htmlspecialchars($tag['Id']) . '">' . htmlspecialchars($tag['Tag']) . '</option>';
                } else {
                    $s .= '<option value="' . htmlspecialchars($tag['Id']) . '" selected>' . htmlspecialchars($tag['Tag']) . '</option>';
                }
            }
            $s .=       '</select><br><span class="note">удерживайте клавишу Ctrl для выделения нескольких тэгов</span>';
                    '</div>' . 
            $s .= '</td></tr>';
        }
        return $s;
    }
    
    protected function setMainTabs()
    {
        parent::setMainTabs();
        $tab = '<a href="?' . $this->config->paramsNames['sectionId'] . '=' . $this->params->sectionId . '&' . $this->config->paramsNames['subModule'] . '=settings"' . ('settings' == $this->params->subModule ? ' class="current"' : '') . ' title="Настройки модуля">настройки</a>';
        $this->appendMainTab('Settings', $tab, 'Items');
        $tab = '<a href="?' . $this->config->paramsNames['sectionId'] . '=' . $this->params->sectionId . '"' . (is_null($this->params->subModule) ? ' class="current"' : '') . ' title="содержимое раздела">содержимое</a>';
        $this->appendMainTab('Items', $tab);
    }
}
