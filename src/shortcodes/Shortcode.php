<?php
namespace lo\plugins\shortcodes;

use yii\helpers\ArrayHelper;

/**
 * Class Shortcode
 * @package lo\plugins\shorcodes
 * @author Lukyanov Andrey <loveorigami@mail.ru>
 */
class Shortcode
{
    /**
     * The regex for attributes.
     *
     * This regex covers the following attribute situations:
     *  - key = "value"
     *  - key = 'value'
     *  - key = value
     *  - "value"
     *  - value
     *
     * @var string
     */
    private $attrPattern = '/(\w+)\s*=\s*"([^"]*)"(?:\s|$)|(\w+)\s*=\s*\'([^\']*)\'(?:\s|$)|(\w+)\s*=\s*([^\s\'"]+)(?:\s|$)|"([^"]*)"(?:\s|$)|(\S+)(?:\s|$)/';

    /**
     * Associative array of shortcodes and their
     * respective callbacks
     */
    private $_shortcodes = [];

    /**
     * @param $tag
     * @return bool
     */
    public function existsShortcode($tag)
    {
        if (isset($this->_shortcodes[$tag])) {
            return true;
        }
        return false;
    }

    /**
     * @param $tag
     * @param array $parser
     * @throws \ErrorException
     */
    public function addShortcode($tag, $parser)
    {
        if ($this->existsShortcode($tag)) {
            return;
        } else {
            $this->_shortcodes[$tag] = $parser;
        }
    }

    /**
     * @param string $tag
     */
    public function removeShortcode($tag)
    {
        if (array_key_exists($tag, $this->_shortcodes)) {
            unset($this->_shortcodes[$tag]);
        }
    }

    /**
     * Clear all shortcodes.
     *
     * This function is simple, it clears all of the shortcode tags by replacing the
     * shortcodes global by a empty array. This is actually a very efficient method
     * for removing all shortcodes.
     *
     * @since 2.5.0
     */
    public function removeAllShortcodes()
    {
        $this->_shortcodes = [];
    }


    /**
     * Tests whether content has a particular shortcode
     * @param $content
     * @param $tag
     * @return bool
     */
    public function hasShortcode($content, $tag)
    {
        if (false === strpos($content, '[')) {
            return false;
        }

        if ($this->existsShortcode($tag)) {
            return true;
        }

        preg_match_all($this->shortcodeRegex(), $content, $matches, PREG_SET_ORDER);

        if (empty($matches)) {
            return false;
        }

        foreach ($matches as $shortcode) {
            if ($tag === $shortcode[2]) {
                return true;
            }
        }

        return false;
    }

    /**
     * Parse shortcodes in given content
     * @param string $content Content to parse for shortcodes
     * @return string
     */
    public function doShortcode($content)
    {
        if (false === strpos($content, '[')) {
            return $content;
        }

        if (empty($this->_shortcodes) || !is_array($this->_shortcodes))
            return $content;

        return preg_replace_callback($this->shortcodeRegex(), [$this, 'doShortcodeTag'], $content);
    }


    /**
     * Parse single shortcode
     * Borrowed from WordPress wp/wp-includes/shortcode.php
     * @param array $m Shortcode matches
     * @return string
     */
    protected function doShortcodeTag($m)
    {
        // allow [[foo]] syntax for escaping a tag
        if ($m[1] == '[' && $m[6] == ']') {
            return substr($m[0], 1, -1);
        }

        $tag = $m[2];
        $attr = $this->parseAttributes($m[3]);

        $callback = $this->_shortcodes[$tag]['callback'];
        $config = $this->_shortcodes[$tag]['config'];

        $attr = ArrayHelper::merge($config, $attr);
        $content = isset($m[5]) ? $m[5] : null;

        $attr['content'] = $content;

        return $m[1] . call_user_func($callback, $attr, $content, $tag) . $m[6];

    }

    /**
     * Remove all shortcode tags from the given content.
     * @uses $shortcode_tags
     * @param string $content Content to remove shortcode tags.
     * @return string Content without shortcode tags.
     */
    public function stripAllShortcodes($content)
    {
        if (empty($this->_shortcodes)) {
            return $content;
        }
        return preg_replace_callback($this->shortcodeRegex(), array($this, 'stripShortcodeTag'), $content);
    }

    /**
     * Strips a tag leaving escaped tags
     * @param $tag
     * @return string
     */
    private function stripShortcodeTag($tag)
    {
        if ($tag[1] == '[' && $tag[6] == ']') {
            return substr($tag[0], 1, -1);
        }
        return $tag[1] . $tag[6];
    }

    /**
     * Get the list of all shortcodes found in given content
     * @param string $content Content to process
     * @return array
     */
    public function getShortcodesFromContent($content)
    {
        if (false === strpos($content, '[')) {
            return [];
        }

        $result = [];

        preg_match_all("/\[([A-Za-z_]+[^\ \]]+)/", $content, $matches);

        if (!empty($matches[1])) {
            foreach ($matches[1] as $match) {
                $result[$match] = $match;
            }
        }

        return $result;
    }

    /**
     * Parses attributes from a shortcode
     * Borrowed from WordPress wp/wp-includes/shortcode.php
     * @param string $text
     * @return array
     */
    protected function parseAttributes($text)
    {
        $text = preg_replace("/[\x{00a0}\x{200b}]+/u", " ", $text);
        if (!preg_match_all($this->attrPattern, $text, $matches, PREG_SET_ORDER)) {
            return array(ltrim($text));
        }
        $attr = array();
        foreach ($matches as $match) {
            if (!empty($match[1])) {
                $attr[strtolower($match[1])] = stripcslashes($match[2]);
            } elseif (!empty($match[3])) {
                $attr[strtolower($match[3])] = stripcslashes($match[4]);
            } elseif (!empty($match[5])) {
                $attr[strtolower($match[5])] = stripcslashes($match[6]);
            } elseif (isset($match[7]) && strlen($match[7])) {
                $attr[] = stripcslashes($match[7]);
            } elseif (isset($match[8])) {
                $attr[] = stripcslashes($match[8]);
            }
        }
        return (array)$attr;
    }

    /**
     * Retrieve the shortcode regular expression for searching.
     *
     * The regular expression combines the shortcode tags in the regular expression
     * in a regex class.
     *
     * The regular expression contains 6 different sub matches to help with parsing.
     *
     * 1 - An extra [ to allow for escaping shortcodes with double [[]]
     * 2 - The shortcode name
     * 3 - The shortcode argument list
     * 4 - The self closing /
     * 5 - The content of a shortcode when it wraps some content.
     * 6 - An extra ] to allow for escaping shortcodes with double [[]]
     *
     * @return string The shortcode search regular expression
     */
    protected function shortcodeRegex()
    {
        $tagRegex = join('|', array_map('preg_quote', array_keys($this->_shortcodes)));
        return
            '/'
            . '\\['                              // Opening bracket
            . '(\\[?)'                           // 1: Optional second opening bracket for escaping shortcodes: [[tag]]
            . "($tagRegex)"                      // 2: Shortcode name
            . '(?![\\w-])'                       // Not followed by word character or hyphen
            . '('                                // 3: Unroll the loop: Inside the opening shortcode tag
            . '[^\\]\\/]*'                   // Not a closing bracket or forward slash
            . '(?:'
            . '\\/(?!\\])'               // A forward slash not followed by a closing bracket
            . '[^\\]\\/]*'               // Not a closing bracket or forward slash
            . ')*?'
            . ')'
            . '(?:'
            . '(\\/)'                        // 4: Self closing tag ...
            . '\\]'                          // ... and closing bracket
            . '|'
            . '\\]'                          // Closing bracket
            . '(?:'
            . '('                        // 5: Unroll the loop: Optionally, anything between the opening and closing shortcode tags
            . '[^\\[]*+'             // Not an opening bracket
            . '(?:'
            . '\\[(?!\\/\\2\\])' // An opening bracket not followed by the closing shortcode tag
            . '[^\\[]*+'         // Not an opening bracket
            . ')*+'
            . ')'
            . '\\[\\/\\2\\]'             // Closing shortcode tag
            . ')?'
            . ')'
            . '(\\]?)'                           // 6: Optional second closing brocket for escaping shortcodes: [[tag]]
            . '/s';
    }
}

