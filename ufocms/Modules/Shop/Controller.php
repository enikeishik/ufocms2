<?php
/**
 * @copyright
 */

namespace Ufocms\Modules\Shop;

use \Ufocms\Modules\ModelInterface;

/**
 * Module level controller
 */
class Controller extends \Ufocms\Modules\Controller //implements ControllerInterface
{
    protected function init()
    {
        parent::init();
        if (null !== $this->moduleParams['cabinet']) {
            $this->module['Model'] .= 'Cabinet';
            $this->module['View'] .= 'Cabinet';
        }
    }
    
    protected function setModuleParamsStruct()
    {
        parent::setModuleParamsStruct();
        $this->moduleParamsStruct = array_merge($this->moduleParamsStruct, 
            array(
                'isYandex'      => ['type' => 'bool',   'from' => 'path',   'prefix' => 'yml',      'additional' => false,  'value' => null, 'default' => false], 
                'order'         => ['type' => 'string', 'from' => 'get',    'prefix' => 'order',    'additional' => false,  'value' => null, 'default' => ''], 
                'cabinet'       => ['type' => 'string', 'from' => 'get',    'prefix' => 'cabinet',  'additional' => false,  'value' => null, 'default' => ''], 
                'categoryId'    => ['type' => 'int',    'from' => 'none',   'prefix' => '',         'additional' => false,  'value' => null, 'default' => 0], 
                'catAlias'      => ['type' => 'string', 'from' => 'path',   'prefix' => '',         'additional' => false,  'value' => null, 'default' => ''], 
                'goodsAlias'    => ['type' => 'string', 'from' => 'path',   'prefix' => '',         'additional' => false,  'value' => null, 'default' => ''], 
                'pageSize'      => ['type' => 'int',    'from' => 'path',   'prefix' => 'psize',    'additional' => true,   'value' => null, 'default' => $this->config->pageSizeDefault], 
            )
        );
    }
    
    protected function modelAction(&$model)
    {
        parent::modelAction($model);
        if (null !== $this->moduleParams['order']) {
            switch ($this->moduleParams['order']) {
                case 'add': //добавление элемента в заказ
                    if (isset($_GET['id'])) {
                        $model->order->addItem((int) $_GET['id'], isset($_GET['count']) ? (int) $_GET['count'] : 1);
                    } else {
                        $model->order->badActionRequest($this->moduleParams['order']);
                    }
                    break;
                case 'remove': //удаление элемента из заказа
                    if (isset($_GET['id'])) {
                        $model->order->removeItem((int) $_GET['id']);
                    } else {
                        $model->order->badActionRequest($this->moduleParams['order']);
                    }
                    break;
                case 'clear': //очистка и удаление всего заказа
                    $model->order->clear();
                    break;
                case 'confirm': //подтверждение отправки заказа
                    if ('POST' != strtoupper($_SERVER['REQUEST_METHOD']) 
                    || !isset($_POST['address']) 
                    || !isset($_POST['email']) 
                    || !isset($_POST['phone']) 
                    || !isset($_POST['comment'])) {
                        $model->order->badActionRequest($this->moduleParams['order']);
                        break;
                    }
                    $address    = strip_tags($_POST['address']);
                    $email      = strip_tags($_POST['email']);
                    $phone      = strip_tags($_POST['phone']);
                    $comment    = strip_tags($_POST['comment']);
                    $model->order->precreate($address, $email, $phone, $comment);
                    break;
                case 'send': //отправка заказа администрации магазина
                    if ('POST' != strtoupper($_SERVER['REQUEST_METHOD'])) {
                        $model->order->badActionRequest($this->moduleParams['order']);
                        break;
                    }
                    $model->order->create();
                    break;
            }
        }
    }
    
    /**
     * @see parent
     */
    protected function getModuleContext(ModelInterface &$model)
    {
        if (null !== $this->moduleParams['order']) {
            //also see modelAction in Controller
            switch ($this->moduleParams['order']) {
                case 'show': //отображение текущего заказа (корзина)
                    return array(
                        'settings'      => null, 
                        'item'          => $model->order->getOrder(), 
                        'items'         => $model->order->getItems(), 
                    );
                    
                case 'add': //добавление элемента в заказ
                case 'remove': //удаление элемента из заказа
                case 'clear': //очистка и удаление всего заказа
                case 'form': //форма заказа для заполнения
                case 'send': //отправка заказа администрации магазина
                    return array(
                        'settings'      => null, 
                        'item'          => null, 
                        'items'         => null, 
                        'actionResult'  => $model->order->getActionResult(), 
                    );
                case 'sended': //заказ отправлен администрации магазина
                    $model->order->setActionResult('sended', true); //поскольку это простой редирект, выставляем флаг результата в true
                    return array(
                        'settings'      => null, 
                        'item'          => null, 
                        'items'         => null, 
                        'actionResult'  => $model->order->getActionResult(), 
                    );
                    
                case 'confirm': //подтверждение отправки заказа
                    return array(
                        'settings'      => null, 
                        'item'          => $model->order->getOrder(), 
                        'items'         => null, 
                        'actionResult'  => $model->order->getActionResult(), 
                    );
                    
                default:
                    $this->core->riseError(404, 'Unknown value of `order` parameter');
            }
            
        } else if ($this->moduleParams['isYandex']) {
            return array(
                'settings'      => null, 
                'item'          => null, 
                'items'         => $model->getItems(), 
                'itemsCount'    => $model->getItemsCount(), 
                'categories'    => $model->getCategories(), 
            );
            
        } else {
            $category = null;
            if (0 != $this->moduleParams['categoryId']) {
                $category = $model->getCategory($this->moduleParams['categoryId']);
            }
            return array_merge(
                parent::getModuleContext($model), 
                array('category' => $category)
            );
        }
    }
    
    /**
     * @see parent
     */
    protected function getLayout()
    {
        if ($this->moduleParams['isYandex']) {
            return 'yml.php';
        } else {
            return parent::getLayout();
        }
    }
}
