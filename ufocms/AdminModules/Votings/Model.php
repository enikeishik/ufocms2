<?php
/**
 * @copyright
 */

namespace Ufocms\AdminModules\Votings;

/**
 * News module model class
 */
class Model extends \Ufocms\AdminModules\Model
{
    /**
     * @see parent
     */
    protected function init()
    {
        parent::init();
        $this->defaultSort = 'DateCreate DESC';
    }
    
    protected function setItems()
    {
        $section = $this->core->getSection();
        $sectionPath = $section['path'];
        unset($section);
        parent::setItems();
        foreach ($this->items as &$item) {
            $item['path'] = $sectionPath . $item['Id'];
        }
        unset($item);
    }
    
    protected function setFields()
    {
        $this->fields = array(
            array('Type' => 'int',          'Name' => 'Id',                 'Value' => 0,                           'Title' => 'id',                        'Filter' => false,  'Show' => true,     'Sort' => true,     'Edit' => false),
            array('Type' => 'list',         'Name' => 'SectionId',          'Value' => $this->params->sectionId,    'Title' => 'Раздел',                    'Filter' => false,  'Show' => false,    'Sort' => false,    'Edit' => true,     'Required' => true,     'Items' => 'getSections'),
            array('Type' => 'datetime',     'Name' => 'DateCreate',         'Value' => date('Y-m-d H:i:s'),         'Title' => 'Дата создания',             'Filter' => false,  'Show' => true,     'Sort' => true,     'Edit' => true,     'Required' => true,     'Class' => 'small'),
            array('Type' => 'datetime',     'Name' => 'DateStart',          'Value' => date('Y-m-d H:i:s'),         'Title' => 'Дата начала',               'Filter' => false,  'Show' => true,     'Sort' => true,     'Edit' => true,     'Required' => true,     'Class' => 'small'),
            array('Type' => 'datetime',     'Name' => 'DateStop',           'Value' => '',                          'Title' => 'Дата окончания',            'Filter' => false,  'Show' => true,     'Sort' => true,     'Edit' => true,     'Required' => true,     'Class' => 'small'),
            array('Type' => 'bool',         'Name' => 'IsDisabled',         'Value' => false,                       'Title' => 'Отключено',                 'Filter' => true,   'Show' => false,    'Sort' => false,    'Edit' => true,     'Items' => array(array('Value' => 1, 'Title' => 'Отключено'), array('Value' => 0, 'Title' => 'Включено'))),
            array('Type' => 'bool',         'Name' => 'IsClosed',           'Value' => false,                       'Title' => 'Завершено',                 'Filter' => true,   'Show' => false,    'Sort' => false,    'Edit' => true,     'Items' => array(array('Value' => 1, 'Title' => 'Отключено'), array('Value' => 0, 'Title' => 'Включено'))),
            /* todo implementation
            array('Type' => 'bool',         'Name' => 'IsMultianswer',      'Value' => false,                       'Title' => 'Мультиответ',               'Filter' => false,  'Show' => false,    'Sort' => false,    'Edit' => true),
            array('Type' => 'int',          'Name' => 'AnswersLimit',       'Value' => 3,                           'Title' => 'Макс. ответов',             'Filter' => false,  'Show' => false,    'Sort' => false,    'Edit' => true,     'Info' => 'Максимальное количество выбранных ответов при мультиответе'),
            */
            array('Type' => 'bool',         'Name' => 'AnswersSeparate',    'Value' => false,                       'Title' => 'Отдельные ответы',          'Filter' => false,  'Show' => false,    'Sort' => false,    'Edit' => true,     'Info' => 'Ответы оформляются не единым списком, а отдельно с кнопкой Проголосовать'),
            array('Type' => 'bool',         'Name' => 'CheckReferer',       'Value' => true,                        'Title' => 'Проверять источник',        'Filter' => false,  'Show' => false,    'Sort' => false,    'Edit' => true,     'Info' => 'Проверяется факт захода на страницу с формой по HTTP-заголовку referer'),
            array('Type' => 'bool',         'Name' => 'CheckTicket',        'Value' => true,                        'Title' => 'Проверять билет',           'Filter' => false,  'Show' => false,    'Sort' => false,    'Edit' => true,     'Info' => 'Проверяется факт захода на страницу с формой по сеансовому билету'),
            array('Type' => 'bool',         'Name' => 'CheckCookie',        'Value' => true,                        'Title' => 'Проверять Cookie',          'Filter' => false,  'Show' => false,    'Sort' => false,    'Edit' => true),
            array('Type' => 'bool',         'Name' => 'CheckIP',            'Value' => false,                       'Title' => 'Проверять IP',              'Filter' => false,  'Show' => false,    'Sort' => false,    'Edit' => true,     'Info' => 'Один голос с одного IP адреса'),
            array('Type' => 'bool',         'Name' => 'CheckUser',          'Value' => false,                       'Title' => 'Только для зарегистр.',     'Filter' => false,  'Show' => false,    'Sort' => false,    'Edit' => true,     'Info' => 'Голосовать могут только зарегистрированные пользователи'),
            array('Type' => 'bool',         'Name' => 'CheckCaptcha',       'Value' => false,                       'Title' => 'Проверять CAPTCHA',         'Filter' => false,  'Show' => false,    'Sort' => false,    'Edit' => true),
            array('Type' => 'list',         'Name' => 'ResultsDisplay',     'Value' => 0,                           'Title' => 'Показывать результаты',     'Filter' => false,  'Show' => false,    'Sort' => false,    'Edit' => true,     'Items' => array(array('Value' => -1, 'Title' => 'Всем'), array('Value' => 0, 'Title' => 'Только проголосовавшим'), array('Value' => 1, 'Title' => 'Только после окончания голосования'))),
            array('Type' => 'int',          'Name' => 'VotesCnt',           'Value' => 0,                           'Title' => 'Голосов',                   'Filter' => false,  'Show' => true,     'Sort' => true,     'Edit' => false),
            array('Type' => 'text',         'Name' => 'Title',              'Value' => '',                          'Title' => 'Заголовок',                 'Filter' => true,   'Show' => true,     'Sort' => true,     'Edit' => true,     'Required' => true,     'Autofocus' => true),
            array('Type' => 'image',        'Name' => 'Image',              'Value' => '',                          'Title' => 'Картинка',                  'Filter' => false,  'Show' => false,    'Sort' => false,    'Edit' => true),
            array('Type' => 'bigtext',      'Name' => 'Description',        'Value' => '',                          'Title' => 'Описание',                  'Filter' => false,  'Show' => false,    'Sort' => false,    'Edit' => true),
        );
    }
}
