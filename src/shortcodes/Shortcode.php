<?php
namespace lo\plugins\shortcodes;

/**
 * Class Shortcode
 * @package lo\plugins\shorcodes
 * @author Lukyanov Andrey <loveorigami@mail.ru>
 */
class Shortcode
{
    /**
     * Associative array of shortcodes and their
     * respective callbacks
     */
    protected $callbacks = [];

    /**
     * @param array $data
     */
    public function registerCallback($data)
    {
        foreach ($data as $key => $value) {
            if (isset($this->callbacks[$key])) {
                continue;
            } else {
                $this->callbacks[$key] = $value;
            }
        }
    }

    /**
     * Parse shortcodes in given content
     * @param string $content Content to parse for shortcodes
     * @return string
     */
    public function parse($content)
    {
        $result = $content;

        $shortcodes = $this->getShortcodeList($content);
        foreach ($shortcodes as $shortcode) {
            // Only process known/supported shortcodes
            if (in_array($shortcode, array_keys($this->callbacks))) {
                $regexp = $this->getShortcodeRegexp($shortcode);
                $result = preg_replace_callback("/$regexp/s", array($this, 'parseSingle'), $result);
            }
        }

        return $result;
    }

    /**
     * Parse single shortcode
     * Borrowed from WordPress wp/wp-includes/shortcode.php
     * @param array $m Shortcode matches
     * @return string
     */
    protected function parseSingle($m)
    {
        // allow [[foo]] syntax for escaping a tag
        if ($m[1] == '[' && $m[6] == ']') {
            return substr($m[0], 1, -1);
        }

        $tag = $m[2];
        $attr = $this->shortcodeParseAtts($m[3]);

        if (isset($m[5])) {
            // enclosing tag - extra parameter
            return $m[1] . call_user_func($this->callbacks[$tag], $attr, $m[5], $tag) . $m[6];
        } else {
            // self-closing tag
            return $m[1] . call_user_func($this->callbacks[$tag], $attr, null, $tag) . $m[6];
        }
    }

    /**
     * Get the list of all shortcodes found in given content
     * @param string $content Content to process
     * @return array
     */
    protected function getShortcodeList($content)
    {
        $result = array();

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
     *
     * Borrowed from WordPress wp/wp-includes/shortcode.php
     *
     * @param string $text
     * @return array
     */
    protected function shortcodeParseAtts($text)
    {
        $atts = array();
        $pattern = '/(\w+)\s*=\s*"([^"]*)"(?:\s|$)|(\w+)\s*=\s*\'([^\']*)\'(?:\s|$)|(\w+)\s*=\s*([^\s\'"]+)(?:\s|$)|"([^"]*)"(?:\s|$)|(\S+)(?:\s|$)/';
        $text = preg_replace("/[\x{00a0}\x{200b}]+/u", " ", $text);
        if (preg_match_all($pattern, $text, $match, PREG_SET_ORDER)) {
            foreach ($match as $m) {
                if (!empty($m[1]))
                    $atts[strtolower($m[1])] = stripcslashes($m[2]);
                elseif (!empty($m[3]))
                    $atts[strtolower($m[3])] = stripcslashes($m[4]);
                elseif (!empty($m[5]))
                    $atts[strtolower($m[5])] = stripcslashes($m[6]);
                elseif (isset($m[7]) and strlen($m[7]))
                    $atts[] = stripcslashes($m[7]);
                elseif (isset($m[8]))
                    $atts[] = stripcslashes($m[8]);
            }
        } else {
            $atts = ltrim($text);
        }
        return $atts;
    }

    /**
     * Returns a regular expression for matching a shortcode tag
     * Borrowed from WordPress wp/wp-includes/shortcode.php
     * @param $tagregexp
     * @return string
     */
    protected function getShortcodeRegexp($tagregexp)
    {
        return
            '\\['                              // Opening bracket
            . '(\\[?)'                           // 1: Optional second opening bracket for escaping shortcodes: [[tag]]
            . "($tagregexp)"                     // 2: Shortcode name
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
            . '(\\]?)';                          // 6: Optional second closing brocket for escaping shortcodes: [[tag]]
    }

}

