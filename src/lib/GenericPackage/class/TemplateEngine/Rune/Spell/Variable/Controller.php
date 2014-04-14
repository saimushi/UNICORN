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
 * @version    SVN: $Id: Controller.php,v 1.1 2009/10/07 06:02:29 syuhei_ono Exp $
 */

require_once 'Rune/Stone.php';
require_once 'Rune/Spell/Common.php';
require_once 'Rune/Spell/Variable.php';

// {{{ Rune_Spell_Variable_Controller

/**
 * DOM parser base template engine.
 *
 * @package    Runemaster
 * @copyright  2008 KUMAKURA Yousuke All rights reserved.
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License (revised)
 * @version    Release: @package_version@
 */
class Rune_Spell_Variable_Controller
{

    // {{{ properties

    /**#@+
     * @access public
     */

    /**#@-*/

    /**#@+
     * @access protected
     */

    protected $_runic;
    protected $_spell;

    /**#@-*/

    /**#@+
     * @access private
     */

    /**#@-*/

    /**#@+
     * @access public
     */

    // }}}
    // {{{ __construct

    /**
     * __construct
     * 
     * @param object $runic
     * @param object $spell
     * @return void
     */
    public function __construct(&$runic, &$spell)
    {
        $this->_runic = $runic;
        $this->_spell = $spell;
    }

    // }}}
    // {{{ createLoop

    /**
     * createLoop
     * 
     * @param  object $dom
     * @return void
     */
    public function createLoop(&$node)
    {
        $loopSource = $this->_spell->getPropertyValue($node->foreach);

        if (is_null($loopSource)) {
            $this->_clearNode($node);
            return false;
        }

        if (is_array($loopSource) && !count($loopSource)) {
            $this->_clearNode($node);
            return false;
        }

        if (!is_array($loopSource) && !is_object($loopSource)) {
            $this->_clearNode($node);
            return false;
        }

        if (!isset($node->as)) {
            $this->_clearNode($node);
            return false;
        } 

        $mapAs = explode(',', str_replace(' ', '', $node->as));
        if (count($mapAs) == 1) {
            $asKey = null;
            $asValue = $mapAs[0];
        } else {
            $asKey = $mapAs[0];
            $asValue = $mapAs[1];
        }
            
        $node->removeAttribute('foreach');
        $node->removeAttribute('as');
        $outertext = $node->outertext;
        $node->outertext = '';

        foreach ($loopSource as $key => $value) {

            $parameter = $this->_runic->getParameter('rune_spell_variable');

            $nestVariables = clone($parameter['variables']);
            if ($asKey) {
                $nestVariables->$asKey = $key;
            }
            $nestVariables->$asValue = $value;

            $nestStone = new Rune_Stone();
            $nestStone->setContent($outertext);

            $runic = clone($this->_runic);
            $runic->setParameter('rune_spell_variable', 'variables', $nestVariables);

            $nestSpell = new Rune_Spell_Variable($runic);
            $nestSpell->carve($nestStone);
            $tmpoutertext = Rune_Master::scan($nestStone);
            // add S.Ohno
            // ループの中で、key名置換を属性に対しても処理した場合の処理を追加
            $tmpoutertext = str_replace("[".$asKey."]",$key,$tmpoutertext);
            $node->outertext .= $tmpoutertext;

            $nestStone->clear();
            $runic->clean();
            unset($runic);
        }

        return true;
    }

    // }}}
    // {{{ adjustIfRule

    /**
     * adjustIfRule
     * 
     * @param  object $node
     * @return void
     */
    public function adjustIfRule(&$node)
    {
        $draft = new Rune_Stone();
        $draft->setContent($node->outertext);
        if (!count(Rune_Master::find($draft, '[if]'))) {
            return false;
        }
        $draft->clear();

        $name = $node->if;
        $value = $this->_spell->getPropertyValue($name);
        if ($value) {
            $node->removeAttribute('if');
        } else {
            $node->outertext = '';
            $node->removeAttribute('if');
            foreach ($node->children as $children) {
                $children->isOutput = false;
            }
        }

        return true;
    }

    /**#@-*/

    /**#@+
     * @access private
     */

    // }}}
    // {{{ _clearNode

    /**
     * _clearNode
     * 
     * @param  object $node
     * @return void
     */
    private function _clearNode(&$node)
    {
        $node->innertext = '';
        $node->removeAttribute('foreach');
        $node->removeAttribute('as');
    }

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
