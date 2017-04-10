<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace svsoft\yii\widgets;

use Yii;
use yii\helpers\Html;

/**
 * Alert widget renders a message from session flash. All flash messages are displayed
 * in the sequence they were assigned using setFlash. You can set message as following:
 *
 * ```php
 * Yii::$app->session->setFlash('error', 'This is the message');
 * Yii::$app->session->setFlash('success', 'This is the message');
 * Yii::$app->session->setFlash('info', 'This is the message');
 * ```
 *
 * Multiple messages could be set as follows:
 *
 * ```php
 * Yii::$app->session->setFlash('error', ['Error 1', 'Error 2']);
 * ```
 *
 * @author Kartik Visweswaran <kartikv2@gmail.com>
 * @author Alexander Makarov <sam@rmcreative.ru>
 */
class FlashMessages extends \yii\bootstrap\Widget
{
    /**
     * @var array the alert types configuration for the flash messages.
     * This array is setup as $key => $value, where:
     * - $key is the name of the session flash variable
     * - $value is the bootstrap alert type (i.e. danger, success, info, warning)
     */

    public $containerId = 'flashes-container';

    public $containerStyle = 'position: fixed; top: 20px; right: 20px; z-index: 1000000';

    public $pjaxRefresh = true;

    public $pjaxRefreshAttribute = 'data-refresh-flash-messages';

    public $timeout = 5000;

    public $alertTypes = [
        'error'   => 'alert-danger',
        'danger'  => 'alert-danger',
        'success' => 'alert-success',
        'info'    => 'alert-info',
        'warning' => 'alert-warning'
    ];
    /**
     * @var array the options for rendering the close button tag.
     */
    public $closeButton = [];


    public function init()
    {
        parent::init();

        $this->timeout = (int)$this->timeout;

        if ($this->timeout<1000)
        {
            $this->timeout = 1000;
        }

        $session = Yii::$app->session;
        $flashes = $session->getAllFlashes();
        $appendCss = isset($this->options['class']) ? ' ' . $this->options['class'] : '';

        $html = '';

        foreach ($flashes as $type => $data) {
            if (isset($this->alertTypes[$type])) {
                $data = (array) $data;
                foreach ($data as $i => $message) {
                    /* initialize css class for each alert box */
                    $this->options['class'] = $this->alertTypes[$type] . $appendCss;

                    /* assign unique id to each alert box */
                    $this->options['id'] = $this->getId() . '-' . $type . '-' . $i;

                    $html .= \yii\bootstrap\Alert::widget([
                        'body' => $message,
                        'closeButton' => $this->closeButton,
                        'options' => $this->options,
                    ]);
                }

                $session->removeFlash($type);
            }
        }

        $html = Html::tag('div', $html, ['id'=>$this->containerId, 'style'=>$this->containerStyle]);

        if ($this->pjaxRefresh)
        {
            \yii\widgets\Pjax::begin(['id'=>$this->containerId . '-pjax', 'linkSelector'=>false, 'enablePushState' => false]);
            echo $html;
            \yii\widgets\Pjax::end();
        }
        else
        {
            echo $html;
        }

        $this->view->registerJs("
            $(document).ready(function(){
                
                var pjaxRefreshAttribute = {$this->pjaxRefresh}?'{$this->pjaxRefreshAttribute}':false;
                
                var timeout = $this->timeout;
                var timeoutOpacity = 1000;

                var init = function(){
                    setTimeout(function(){                    
                        $('#{$this->containerId} .alert').animate({opacity:0}, timeoutOpacity, function(){
                            $(this).remove();
                        });
                    }, timeout - timeoutOpacity);
                };
                
                if ($('#{$this->containerId} .alert').size())
                {
                    init();
                }
                
                if (pjaxRefreshAttribute)
                {
                    var pjaxContainers = $('['+pjaxRefreshAttribute+']');
                    
                    pjaxContainers.off('pjax:end');
                    
                    pjaxContainers.on('pjax:end', function() {
                        $.pjax.reload({container:'#{$this->containerId}-pjax'});
                    });
                    
                    $('#{$this->containerId}-pjax').on('pjax:end', function(){
                        init();
                    });     
                }
            });
        ");
    }
}
