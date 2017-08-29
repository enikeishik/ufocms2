<?php
/**
 * @copyright
 */

namespace Ufocms\AdminModules\CoreInteraction;

/**
 * Module level UI
 */
class UI extends \Ufocms\AdminModules\UI
{
    /**
     * @see parent
     */
    protected function init()
    {
        $this->section = array('path' => '', 'title' => 'Интерактив');
    }
    
    /**
     * @see parent
     */
    protected function setItemFuncs(array $item, array $funcs)
    {
        parent::setItemFuncs($item, $funcs);
        $s = ' <a href="' . $this->basePath . '&' . $this->config->paramsNames['action'] . '=blacklist&' . $this->config->paramsNames['itemId'] . '=' . $item['itemid'] . '" title="Добавить IP в черный список">в ч.с.</a>';
        $this->appendItemFunc('blacklist', $s);
    }
    
    protected function getStatusView($status)
    {
        if (0 < $status) {
            return ':)';
        } else if (0 > $status) {
            return ':(';
        } else {
            return ':|';
        }
    }
    
    protected function getUserView($item)
    {
        if (is_null($item['UserTitle'])) {
            return '(не зарегистрирован)';
        } else {
            return '<a href="/users/' . $item['UserId'] . '/" target="_blank">' . $item['UserTitle'] . '</a>';
        }
    }
    
    protected function getPageView($item)
    {
        if (0 != $item['ItemId']) {
            return  '<a href="' . $item['path'] . '" target="_blank">' . htmlspecialchars($item['indic']) . '</a>' . 
                    ' / ' . 
                    '<a href="' . $item['path'] . $item['ItemId'] . '/" target="_blank">' . $item['ItemId'] . '</a>';
        } else {
            return  '<a href="' . $item['path'] . '" target="_blank">' . htmlspecialchars($item['indic']) . '</a>';
        }
    }
    
    /**
     * @see parent
     */
    public function singleItems()
    {
        $fields = $this->model->getFields();
        $items = $this->model->getItems();
        
        $s = '<div class="items">';
        foreach ($items as $item) {
            $disabled = (array_key_exists('disabled', $item) && $item['disabled']) ? ' disabled' : '';
            $s .= '<div class="item' . $disabled . '">';
            
            $s .=   '<div class="itemhead">' . 
                        '<div class="funcs">' . $this->getItemFuncsHtml($item, array('delconfirm', 'disable', 'edit')) . '</div>' . 
                        '<div class="itemfield type-int">' . 
                            '<span class="fieldname">id</span>' . 
                            '<span class="fieldvalue">' . $item['Id'] . '</span>' . 
                        '</div>' . 
                        '<div class="itemfield type-datetime">' . 
                            '<span class="fieldname">Дата/время</span>' . 
                            '<span class="fieldvalue">' . $item['DateCreate'] . '</span>' . 
                        '</div>' . 
                        '<div class="itemfield type-text">' . 
                            '<span class="fieldname">IP</span>' . 
                            '<span class="fieldvalue">' . htmlspecialchars($item['IP']) . '</span>' . 
                        '</div>' . 
                        '<div class="itemfield type-text">' . 
                            '<span class="fieldname">Пользователь</span>' . 
                            '<span class="fieldvalue">' . $this->getUserView($item) . '</span>' . 
                        '</div>' . 
                        '<br>' . 
                        '<div class="itemfield type-text">' . 
                            '<span class="fieldname">Страница сайта</span>' . 
                            '<span class="fieldvalue">' . $this->getPageView($item) . '</span>' . 
                        '</div>' . 
                        '<div class="itemfield type-text">' . 
                            '<span class="fieldname">Рейтинг</span>' . 
                            '<span class="fieldvalue">' . 
                                $item['Rating'] . 
                                (0 < $item['RatesCnt'] ? 
                                ' (<a href="' . 
                                    '?' . $this->config->paramsNames['coreModule'] . '=' . $this->params->coreModule . 
                                    '&' . $this->config->paramsNames['subModule'] . '=commentrates' . 
                                    '&commentid=' . $item['itemid'] . 
                                '">голосов</a>: ' . $item['RatesCnt'] . ')'
                                : '') . 
                            '</span>' . 
                        '</div>' . 
                    '</div>' . "\r\n";
            
            $s .=   '<div class="itembody">' . 
                        '<div class="itemfield type-bigext">' . 
                            '<div class="fieldname">Комментарий</div>' . 
                            '<div class="fieldvalue">' . htmlspecialchars($item['CommentText']) . '</div>' . 
                        '</div>' . 
                    '</div>' . "\r\n" . 
                    '<div class="itemhead">' . 
                        '<div class="itemfield type-text">' . 
                            '<span class="fieldname">Подпись</span>' . 
                            '<span class="fieldvalue">' . htmlspecialchars($item['CommentAuthor']) . '</span>' . 
                        '</div>' . 
                        '<div class="itemfield type-text">' . 
                            '<span class="fieldname">Email</span>' . 
                            '<span class="fieldvalue">' . htmlspecialchars($item['CommentEmail']) . '</span>' . 
                        '</div>' . 
                        '<div class="itemfield type-text">' . 
                            '<span class="fieldname">Url</span>' . 
                            '<span class="fieldvalue">' . htmlspecialchars($item['CommentUrl']) . '</span>' . 
                        '</div>' . 
                        '<div class="itemfield type-int">' . 
                            '<span class="fieldname">Статус</span>' . 
                            '<span class="fieldvalue">' . $this->getStatusView($item['CommentStatus']) . '</span>' . 
                        '</div>' . 
                    '</div>' . "\r\n";
            
            if ('' != $item['AnswerText']) {
                $s .=   '<div class="itembody">' . 
                            '<div class="itemfield type-bigext">' . 
                                '<div class="fieldname">Ответ</div>' . 
                                '<div class="fieldvalue">' . htmlspecialchars($item['AnswerText']) . '</div>' . 
                            '</div>' . 
                        '</div>' . "\r\n" . 
                        '<div class="itemhead">' . 
                            '<div class="itemfield type-text">' . 
                                '<span class="fieldname">Подпись</span>' . 
                                '<span class="fieldvalue">' . htmlspecialchars($item['AnswerAuthor']) . '</span>' . 
                            '</div>' . 
                            '<div class="itemfield type-text">' . 
                                '<span class="fieldname">Email</span>' . 
                                '<span class="fieldvalue">' . htmlspecialchars($item['AnswerEmail']) . '</span>' . 
                            '</div>' . 
                            '<div class="itemfield type-text">' . 
                                '<span class="fieldname">Url</span>' . 
                                '<span class="fieldvalue">' . htmlspecialchars($item['AnswerUrl']) . '</span>' . 
                            '</div>' . 
                        '</div>' . "\r\n";
            }
            
            $s .= '</div>';
        }
        return $s . '</div>';
    }
    
    /**
     * @see parent
     */
    protected function setMainTabs()
    {
        parent::setMainTabs();
        $tab = '<a href="?' . $this->config->paramsNames['coreModule'] . '=' . $this->params->coreModule . '" class="current" title="комментарии">комментарии</a>';
        $this->appendMainTab('Items', $tab);
        $tab = '<a href="?' . $this->config->paramsNames['coreModule'] . '=' . $this->params->coreModule . '&' . $this->config->paramsNames['subModule'] . '=rates" title="оценки">оценки</a>';
        $this->appendMainTab('Rates', $tab);
        $tab = '<a href="?' . $this->config->paramsNames['coreModule'] . '=' . $this->params->coreModule . '&' . $this->config->paramsNames['subModule'] . '=blacklist" title="Черный список">Ч.С.</a>';
        $this->appendMainTab('Blacklist', $tab);
    }
}
