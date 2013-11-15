<?php
 /**
 * Copyright (c) 2013 Molchanov Ivan
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy of
 * this software and associated documentation files (the "Software"), to deal in
 * the Software without restriction, including without limitation the rights to
 * use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of
 * the Software, and to permit persons to whom the Software is furnished to do so,
 * subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS
 * FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR
 * COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER
 * IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN
 * CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */

namespace Opensoft\MonkeyCodeBundle\Service;

use Doctrine\Common\Persistence\ObjectRepository;

use JMS\DiExtraBundle\Annotation\Service;

/**
 * Opensoft\MonkeyCodeBundle\Service\BBCodeRenderer
 *
 * @Service("opensoft_monkey_code.bbcode_renderer")
 *
 * @author Ivan Molchanov <ivan.molchanov@opensoftdev.ru>
 */
class BBCodeRenderer 
{
    /**
     * @param $string
     * @return mixed|string
     */
    public function render($string)
    {
        $string = htmlspecialchars($string);
        $string = str_replace('\\', '&#92;', $string);
        $string = preg_replace("#(\\[b\\])(.*?[^\\[/b\\]]?)(\\[/b\\])#sim", "<b>\$2</b>", $string);
        $string = preg_replace("#(\\[i\\])(.*?[^\\[/i\\]]?)(\\[/i\\])#sim", "<i>\$2</i>", $string);
        $string = preg_replace("#(\\[u\\])(.*?[^\\[/u\\]]?)(\\[/u\\])#sim", "<u>\$2</u>", $string);
        $string = preg_replace("#(\\[s\\])(.*?[^\\[/s\\]]?)(\\[/s\\])#sim", "<s>\$2</s>", $string);
        $string = preg_replace("#(\\[sub\\])(.*?[^\\[/sub\\]]?)(\\[/sub\\])#sim", "<sub>\$2</sub>", $string);
        $string = preg_replace("#(\\[sup\\])(.*?[^\\[/sup\\]]?)(\\[/sup\\])#sim", "<sup>\$2</sup>", $string);
        $tags = array('list' => '<ul>', 'num' => '<ol>', 'quote' => '<div class="quote"><pre>',);
        foreach ($tags as $tag => $val) {
            if ($tag == 'list') {
                $regExp = '#(\\[list\\])(.*?[^\\[/list\\]]?)(\\[/list\\])#sim';
                $vt = preg_match_all($regExp, $string, $match);
                for ($i = 0; $i < $vt; $i++) {
                    $string = preg_replace($regExp, "$val\$2</ul>", $string, 1);
                    $withBreaks = str_replace('[*]', '<li>&nbsp;', $match[2][$i]);
                    $string = str_replace($match[2][$i], $withBreaks, $string);
                }
            }
            if ($tag == 'num') {
                $regExp = '#(\\[num\\])(.*?[^\\[/num\\]]?)(\\[/num\\])#sim';
                $vt = preg_match_all($regExp, $string, $match);
                for ($i = 0; $i < $vt; $i++) {
                    $string = preg_replace($regExp, "$val\$2</ol>", $string, 1);
                    $withBreaks = str_replace('[*]', '<li>&nbsp;', $match[2][$i]);
                    $string = str_replace($match[2][$i], $withBreaks, $string);
                }
            }
            if ($tag == 'quote') {
                $regExp = '#(\\[quote\\])(.*?[^\\[/quote\\]]?)(\\[/quote\\])#sim';
                $vt = preg_match_all($regExp, $string, $match);
                for ($i = 0; $i < $vt; $i++) {
                    $string = preg_replace($regExp, "$val\$2</pre></div>", $string, 1);
                    $withBreaks = $match[2][$i];
                    $string = str_replace($match[2][$i], $withBreaks, $string);
                }
            }
        }
        $imgRegExp = '#(\\[img) ?(align=)?(left|right|middle|top|bottom)?(\\])(.*?[^\\[/img\\]]?)(\\[/img\\])#sim';
        $vt = preg_match_all($imgRegExp, $string, $match);
        for ($i = 0; $i < $vt; $i++) {
            if (!empty($match[3][$i])) {
                $string = preg_replace(
                    $imgRegExp,
                    "<img src=\"\$5\" align=\"$3\" style=\"max-width: 1024px;\" alt=\"[incorrect path to image]\" />",
                    $string,
                    1
                );
            } else {
                $string = preg_replace(
                    $imgRegExp,
                    "<img src=\"\$5\" style=\"max-width: 1024px;\" alt=\"[incorrect path to image]\" />",
                    $string,
                    1
                );
            }
        }
        $urlRegExp = '#(\\[url\\])(.*?[^\\[/url\\]]?)(\\[/url\\])#sim';
        $vt = preg_match_all($urlRegExp, $string, $match);
        for ($i = 0; $i < $vt; $i++) {
            if (filter_var($match[2][$i], FILTER_VALIDATE_URL)) {
                $string = preg_replace($urlRegExp, "<a href=\"\$2\">\$2</a>", $string);
            }
        }
        $parametrisedUrlRegExp = '#(\\[url=)(.*?[^\\]]?)(\\])(.*?[^\\[/url\\]]?)(\\[/url\\])#sim';
        $vt = preg_match_all($parametrisedUrlRegExp, $string, $match);
        for ($i = 0; $i < $vt; $i++) {
            if (filter_var($match[2][$i], FILTER_VALIDATE_URL)) {
                $string = preg_replace($parametrisedUrlRegExp, "<a href=\"\$2\">\$4</a>", $string);
            }
        }
        $string = '<p>' . $string . '</p>';
        $string = str_replace("\r\n", '</p><p>', $string);

        return $string;
    }
} 
