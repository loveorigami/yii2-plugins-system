<?php
namespace lo\plugins\plugins\code;

use lo\plugins\shortcodes\ShortcodeWidget;
use yii\helpers\Html;
use yii\web\View;

/**
 * Class CodeWidget
 * @package lo\plugins\plugins\code
 * @author Lukyanov Andrey <loveorigami@mail.ru>
 */
class CodeWidget extends ShortcodeWidget
{
    public $style = 'github';
    public $lang = 'php';

    /**
     * @return string
     */
    public function run()
    {
        $this->registerAsset();
        $tag[] = Html::beginTag('pre');
        $tag[] = Html::tag('code', $this->content, ['class' => $this->lang]);
        $tag[] = Html::endTag('pre');
        return implode('', $tag);
    }

    /**
     * register Highlighting asset
     */
    protected function registerAsset()
    {
        /** @var View $view */
        $view = $this->getView();

        CodeAsset::$style = $this->style;
        CodeAsset::register($view);

        $view->registerJs("hljs.initHighlightingOnLoad();");
    }
}