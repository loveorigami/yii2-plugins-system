<?php
/**
 * EventManager class file.
 * @copyright (c) 2013, Galament
 * @license http://www.opensource.org/licenses/bsd-license.php
 */

namespace lo\plugins\components;

use yii\web\View as WebView;

/**
 * Class View
 * @package lo\plugins\components
 * @author Lukyanov Andrey <loveorigami@mail.ru>
 */
class View extends WebView
{
    /**
     * @event Event an event that is triggered by [[contentManipulation()]].
     */
    const EVENT_DO_BODY = 'doBody';

    /**
     * @var string
     */
    private $_body;

    /**
     * Marks the beginning of an HTML body section.
     */
    public function beginBody()
    {
        echo self::PH_BODY_BEGIN;
        $this->trigger(self::EVENT_BEGIN_BODY);
    }

    /**
     * Content manipulation. Need for correct replacement shortcodes
     */
    public function doBody()
    {
        if ($this->hasEventHandlers(self::EVENT_DO_BODY)) {
            $event = new ViewEvent([
                'content' => $this->_body,
            ]);
            $this->trigger(self::EVENT_DO_BODY, $event);
            $this->_body = $event->content;
        }
    }

    /**
     * Renders a view in response to an AJAX request.
     *
     * This method is similar to [[render()]] except that it will surround the view being rendered
     * with the calls of [[beginPage()]], [[head()]], [[beginBody()]], [[endBody()]] and [[endPage()]].
     * By doing so, the method is able to inject into the rendering result with JS/CSS scripts and files
     * that are registered with the view.
     *
     * @param string $view the view name. Please refer to [[render()]] on how to specify this parameter.
     * @param array $params the parameters (name-value pairs) that will be extracted and made available in the view file.
     * @param object $context the context that the view should use for rendering the view. If null,
     * existing [[context]] will be used.
     * @return string the rendering result
     * @see render()
     */
    public function renderAjax($view, $params = [], $context = null)
    {
        $viewFile = $this->findViewFile($view, $context);
        $this->_body = $this->renderFile($viewFile, $params, $context);

        ob_start();
        ob_implicit_flush(false);

        $this->beginPage();
        $this->head();
        $this->beginBody();
        $this->doBody();
        echo $this->_body;
        $this->endBody();
        $this->endPage(true);

        return ob_get_clean();
    }

    /**
     * Marks the ending of an HTML body section.
     */
    public function endBody()
    {
        if (!$this->_body) {
            $this->_body = ob_get_clean();
            $this->doBody();
            ob_start();
        }

        $this->trigger(self::EVENT_END_BODY);
        echo self::PH_BODY_END;

        foreach (array_keys($this->assetBundles) as $bundle) {
            $this->registerAssetFiles($bundle);
        }
    }

    /**
     * Marks the ending of an HTML page.
     * @param bool $ajaxMode whether the view is rendering in AJAX mode.
     * If true, the JS scripts registered at [[POS_READY]] and [[POS_LOAD]] positions
     * will be rendered at the end of the view like normal scripts.
     */
    /**
     * Marks the ending of an HTML page.
     * @param bool $ajaxMode whether the view is rendering in AJAX mode.
     * If true, the JS scripts registered at [[POS_READY]] and [[POS_LOAD]] positions
     * will be rendered at the end of the view like normal scripts.
     */
    public function endPage($ajaxMode = false)
    {
        $this->trigger(self::EVENT_END_PAGE);
        $endPage = ob_get_clean();

        if ($ajaxMode) {
            $content = $endPage;
        } else {
            $content = $this->_body . $endPage;
        }

        echo strtr($content, [
            self::PH_HEAD => $this->renderHeadHtml(),
            self::PH_BODY_BEGIN => $this->renderBodyBeginHtml(),
            self::PH_BODY_END => $this->renderBodyEndHtml($ajaxMode),
        ]);

        $this->clear();
    }
}