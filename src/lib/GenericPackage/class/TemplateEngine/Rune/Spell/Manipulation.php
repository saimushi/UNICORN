<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */

/* 
 * PHP versions 5
 *
 * Copyright (c) 2008 KUMAKURA Yousuke All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are met:
 *
 *     * Redistributions of source code must retain the above copyright
 *       notice, this list of conditions and the following disclaimer.
 *     * Redistributions in binary form must reproduce the above copyright
 *       notice, this list of conditions and the following disclaimer in the
 *       documentation and/or other materials provided with the distribution.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
 * AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE
 * ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE
 * LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR
 * CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF
 * SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS
 * INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN
 * CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)
 * ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 *
 * @package    Runemaster
 * @copyright  2008 KUMAKURA Yousuke All rights reserved.
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License (revised)
 * @version    SVN: $Id: Manipulation.php,v 1.1 2009/10/07 06:02:28 syuhei_ono Exp $
 */

require_once 'Rune/Spell/Common.php';

// {{{ Rune_Spell_Manipulation

/**
 * DOM parser base template engine.
 *
 * @package    Runemaster
 * @copyright  2008 KUMAKURA Yousuke All rights reserved.
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License (revised)
 * @version    Release: @package_version@
 */
class Rune_Spell_Manipulation extends Rune_Spell_Common
{

    // {{{ properties

    /**#@+
     * @access public
     */

    /**#@-*/

    /**#@+
     * @access protected
     */

    /**#@-*/

    /**#@+
     * @access private
     */

    /**#@-*/

    /**#@+
     * @access public
     */

    // }}}
    // {{{ carve

    /**
     * carve
     * 
     * @param  object $dom
     * @param  mixed  $appendixes
     * @return boolean
     */
    public function carve(&$stone)
    {
        $isManipulated = null;

        $appendixes = $this->getParameter('appendixes');
        $prepends   = $this->getParameter('prepends');

        if (is_array($appendixes)) {
            foreach ($appendixes as $appendix) {
                foreach (Rune_Master::find($stone, $appendix->selector) as $node) {
                    if ($appendix->content instanceof html_dom_node) {
                        $node->innertext .= $appendix->content->outertext;
                    } else {
                        $node->innertext .= $appendix->content;
                    }
                    $isManipulated = true;
                }
            }
        }

        if (is_array($prepends)) {
            foreach ($prepends as $prepend) {
                foreach (Rune_Master::find($stone, $prepend->selector) as $node) {
                    if ($prepend->content instanceof html_dom_node) {
                        $node->innertext = $prepend->content->outertext .
                            $node->innertext;
                    } else {
                        $node->innertext = $prepend->content . $node->innertext;
                    }
                    $isManipulated = true;
                }
            }
        }

        if ($isManipulated === true) {
            $stone->refresh();
        }
    }

    // }}}
    // {{{ append

    /**
     * append
     * 
     * @param  string $selector
     * @param  mixed  $content
     * @return void
     */
    public function append($selector, $content)
    {
        $appendixes = $this->getParameter('appendixes');

        $appendix = new stdClass();
        $appendix->selector = $selector;
        $appendix->content = $content;
        array_push($appendixes, $appendix);

        $this->setParameter('appendixes', $appendixes);
    }

    // }}}
    // {{{ prepend

    /**
     * prepend
     * 
     * @param  string $selector
     * @param  mixed  $content
     * @return void
     */
    public function prepend($selector, $content)
    {
        $prepends = $this->getParameter('prepends');

        $prepend = new stdClass();
        $prepend->selector = $selector;
        $prepend->content = $content;
        array_push($prepends, $prepend);

        $this->setParameter('prepends', $prepends);
    }

    // }}}
    // {{{ initialize

    /**
     * initialize
     * 
     * @return mixed
     */
    public function initialize()
    {
        $this->addMethod('append', 'append');
        $this->addMethod('prepend', 'prepend');
    }

    /**#@-*/

    /**#@+
     * @access private
     */

    /**#@-*/

    // }}}
}

// }}}

/*
 * Local Variables:
 * mode: php
 * coding: utf-8
 * tab-width: 4
 * c-basic-offset: 4
 * c-hanging-comment-ender-p: nil
 * indent-tabs-mode: nil
 * End:
 */
?>
