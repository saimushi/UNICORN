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
 * @version    SVN: $Id: Master.php,v 1.1 2009/10/07 06:02:25 syuhei_ono Exp $
 */

require_once dirname(__FILE__) . '/imports/simple_html_dom.php';
require_once 'Rune/Runic.php';
require_once 'Rune/Stone.php';

// {{{ Rune_Master

/**
 * DOM parser base template engine.
 *
 * @package    Runemaster
 * @copyright  2008 KUMAKURA Yousuke All rights reserved.
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License (revised)
 * @version    Release: @package_version@
 */
class Rune_Master
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

    protected $_spells;
    protected $_templateDirectory;
    protected $_templateSuffix = '.html';

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
     * @param string $templateDirectory
     * @return void
     */
    public function __construct($templateDirectory)
    {
        $this->_runic = new Rune_Runic();
        $this->_runic->setTemplateDirectory($templateDirectory);

        $this->_templateDirectory = $templateDirectory;
        $spells = array(
                        'Rune_Spell_Layout',
                        'Rune_Spell_Manipulation',
                        'Rune_Spell_Attribute',
                        'Rune_Spell_Variable',
                        'Rune_Spell_HiddenValue',
                        'Rune_Spell_Option',
                        'Rune_Spell_FormValue'
                        );
        $this->setSpells($spells);
   }

    // }}}
    // {{{ __call

    /**
     * __call - executes spells method.
     * 
     * @param string $methodName
     * @param array $params
     * @return void
     */
    public function __call($methodName, $params)
    {
        $methodName = strtolower($methodName);
        $spellMethods = $this->_runic->getSpellMethods();

        if (array_key_exists($methodName, $spellMethods)) {
            $spellMethod = $spellMethods[$methodName];
            $spell = &$this->_runic->getSpell($spellMethod->name);
            call_user_func_array(array($spell, $spellMethod->method), $params);
        } else {
            throw new Exception("Call to undefined Runemaster's method [$methodName].");
        }
    }

    // }}}
    // {{{ cast

    /**
     * Casts html.
     * 
     * @param string $templateName
     * @param array  $variables
     * @return void
     */
    public function cast($templateName, $variables = null)
    {
        $this->_runic->setTemplate($templateName);
        $this->_runic->setTemplateSuffix($this->_templateSuffix);

        $filename = "{$templateName}{$this->_templateSuffix}";
        $templateFile = "{$this->_templateDirectory}/{$filename}";

        $stone = new Rune_Stone();

        try {
            $stone->setTemplate($templateFile, $variables);
            $spells = $this->_runic->getSpells();
            foreach ($spells as $spell) {
                $spell->carve($stone);
            }
            echo $this->scan($stone);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    // }}}
    // {{{ scan

    /**
     * Reads the html content from Rune_Stone.
     * 
     * @param  object $stone
     * @return mixed
     */
    static public function scan(&$stone)
    {
        $dom = &$stone->getDom();
        return $dom->save();
    }

    // }}}
    // {{{ find

    /**
     * Finds the nodes by selector from Rune_Stone.
     * 
     * @param  object $stone
     * @param  string $selector
     * @return mixed
     */
    static public function find(&$stone, $selector)
    {
        if ($stone instanceof Rune_Stone) {
            $target = &$stone->getDom();
        } else {
            $target = &$stone;
        }

        return $target->find($selector);
    }

    // }}}
    // {{{ findAll

    /**
     * Finds all nodes from Rune_Stone.
     * 
     * @param  object $stone
     * @return mixed
     */
    static public function findAll(&$stone)
    {
        $target = &$stone->getDom();
        return $target->nodes;
    }

    // }}}
    // {{{ findByKeyRegex

    /**
     * Finds the nodes by attribute key names regex from Rune_Stone.
     * 
     * @param  object $stone
     * @param  string $selector
     * @return mixed
     */
    static public function findByKeyRegex(&$stone, $selector)
    {
        $target = &$stone->getDom();
        $nodes = array();

        foreach ($target->nodes as $node) {
            if ($node->nodetype !== HDOM_TYPE_ELEMENT) {
                continue;
            }

            foreach ($node->attr as $key => $value) {
                if (preg_match("/{$selector}/", $key, $matches)) {
                    array_push($nodes, $node);
                }
            }
        }

        return $nodes;
    }

    // }}}
    // {{{ findByValueRegex

    /**
     * Finds the nodes by attribute values regex from Rune_Stone.
     * 
     * @param  object $stone
     * @param  string $selector
     * @return mixed
     */
    static public function findByValueRegex(&$stone, $selector)
    {
        $target = &$stone->getDom();
        $nodes = array();
        
        foreach ($target->nodes as $node) {
            if ($node->nodetype !== HDOM_TYPE_ELEMENT) {
                continue;
            }

            foreach ($node->attr as $key => $value) {
                if (preg_match("/{$selector}/", $value, $matches)) {
                    array_push($nodes, $node);
                }
            }
        }

        return $nodes;
    }

    // }}}
    // {{{ setTemplateSuffix

    /**
     * Sets template suffix name.
     * 
     * @param string $suffix
     * @return void
     */
    public function setTemplateSuffix($suffix)
    {
        $this->_templateSuffix = $suffix;
    }

    // }}}
    // {{{ listSpell

    /**
     * Lists rune spells.
     * 
     * @return array
     */
    public function listSpell()
    {
        return $this->_spells;
    }

    // }}}
    // {{{ setSpells

    /**
     * Sets rune spell.
     * 
     * @param array $spells
     * @return void
     */
    public function setSpells($spells)
    {
        $this->_spells = array();
        $this->_runic->clean();

        foreach ($spells as $spell) {
            $this->addSpell($spell);
        }
    }

    // }}}
    // {{{ addSpell

    /**
     * Adds new rune spell.
     * 
     * @param string $spell
     * @return void
     */
    public function addSpell($spell)
    {
        array_push($this->_spells, $spell);

        $spellPath = str_replace('_', '/', $spell) . '.php';
        $spellName = strtolower(basename($spellPath, '.php'));

        require_once $spellPath;
        $spellObject = new $spell($this->_runic);

        $spellObject->initialize();
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
