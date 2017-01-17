<?php

namespace lo\plugins\services;

use lo\plugins\dto\ShortcodesDbCallbacksDto;
use lo\plugins\repositories\ShortcodeDbRepository;
use lo\plugins\shortcodes\ShortcodeParser;


class ShortcodeService
{
    /**
     * @var ShortcodeParser
     */
    private $shortcodeParser;

    /**
     * @var ShortcodeDbRepository
     */
    private $shortcodeDbRepository;

    /**
     * @var array
     */
    private $shortcodesCallback = [];

    /**
     * ShortcodeService constructor.
     * @param ShortcodeParser $shortcodeParser
     * @param ShortcodeDbRepository $shortcodeDbRepository
     */
    public function __construct(
        ShortcodeParser $shortcodeParser,
        ShortcodeDbRepository $shortcodeDbRepository
    )
    {
        $this->shortcodeParser = $shortcodeParser;
        $this->shortcodeDbRepository = $shortcodeDbRepository;
    }

    /**
     * @param $blocks
     */
    public function addIgnoreBlocks($blocks)
    {
       $this->shortcodeParser->addIgnoreBlocks($blocks);
    }

    /**
     * @param $content
     * @return array
     */
    public function getShortcodesFromContent($content)
    {
        return $this->shortcodeParser->getShortcodesFromContent($content);
    }

    /**
     * @param $shortcodes
     * @param $appId
     */
    public function setShortcodesFromDb($shortcodes, $appId)
    {
        $shortcodes = $this->shortcodeDbRepository->findShortcodesByNameAsArray($shortcodes, $appId);
        if ($shortcodes) {
            $callbacs = new ShortcodesDbCallbacksDto($shortcodes);
            $this->shortcodesCallback = $callbacs->data;
        }
    }

    /**
     * @param $content
     * @return string
     */
    public function parseShortcodes($content)
    {
        if (!$this->shortcodesCallback) return $content;

        foreach ($this->shortcodesCallback as $parser) {
            $this->shortcodeParser->addShortcode($parser);
        }

        return $this->shortcodeParser->doShortcode($content);
    }

}
