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
 * @version    SVN: $Id: Layout.php,v 1.1 2009/10/07 06:02:28 syuhei_ono Exp $
 */

require_once 'Rune/Spell/Common.php';

// {{{ Rune_Spell_Layout

/**
 * DOM parser base template engine.
 *
 * @package    Runemaster
 * @copyright  2008 KUMAKURA Yousuke All rights reserved.
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License (revised)
 * @version    Release: @package_version@
 */
class Rune_Spell_Layout extends Rune_Spell_Common
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
     * @return void
     */
    public function carve(&$stone)
    {
        $layout = $this->getParameter('layout');
        if (!$layout) {
            return;
        }

        $templateDirectory = $this->getParameter('layoutDirectory');
        if (!$templateDirectory) {
            $templateDirectory = $this->_runic->getTemplateDirectory();
        }

        $templateSuffix = $this->_runic->getTemplateSuffix();

        $filename = "{$layout}{$templateSuffix}";
        $layoutFile = "{$templateDirectory}/{$filename}";

        $layoutStone = new Rune_Stone();

        $contents = null;
        $contentNodes = Rune_Master::find($stone, '[contents]');
        if (count($contentNodes)) {
            foreach ($contentNodes as $node) {
                if ($node->contents === true
                    || $node->contents === 'inner'
                    ) {
                    $contents .= $node->innertext;
                } elseif ($node->contents === 'outer') {
                    $node->removeAttribute('contents');
                    $contents .= $node->outertext;
                }
            }
        } else {
            $contents = Rune_Master::scan($stone);
        }

        try {
            $layoutStone->setTemplate($layoutFile);
            foreach (Rune_Master::find($layoutStone, '[content_for_layout]') as $node) {
                if ($node->content_for_layout === true
                    || $node->content_for_layout === 'inner'
                    ) {
                    $node->innertext = $contents;
                    $node->removeAttribute('content_for_layout');
                } elseif ($node->content_for_layout === 'outer') {
                    $node->outertext = $contents;
                }
            }

            $stone = $layoutStone;
            $stone->refresh();
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    // }}}
    // {{{ setLayout

    /**
     * setLayout
     * 
     * @param  string $layout
     * @return void
     */
    public function setLayout($layout, $directory = null)
    {
        $this->setParameter('layout', $layout);
        $this->setParameter('layoutDirectory', $directory);
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
        $this->addMethod('setLayout', 'setLayout');
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
