<?php
namespace lo\plugins\plugins\youtube;
use lo\plugins\shortcodes\ShortcodeWidget;

use yii\helpers\Html;

/**
 * Class YoutubeWidget
 * @package lo\plugins\shortcodes
 * @author Lukyanov Andrey <loveorigami@mail.ru>
 */
class YoutubeWidget extends ShortcodeWidget
{
    /**
     * @var string
     */
    public $code;

    /**
     * @var string
     */
    public $w;

    /**
     * @var string
     */
    public $h;

    /**
     * @var string url pattern for video content
     */
    public $embedPattern = 'https://www.youtube.com/embed/{video_id}';

    /**
     * @var array
     */
    public $playerParameters;

    /**
     * @var array
     */
    protected $iframeOptions;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $this->iframeOptions = [
            'width' => $this->w,
            'height' => $this->h,
            'frameborder' => 0
        ];
    }

    public function run()
    {
        $url = str_replace('{video_id}', $this->code, $this->embedPattern);
        if (!empty($this->playerParameters)) {
            $url .= '?' . http_build_query($this->playerParameters);
        }
        $options = array_merge(['src' => $url], $this->iframeOptions);
        echo Html::tag('iframe', '', $options);
    }

}

