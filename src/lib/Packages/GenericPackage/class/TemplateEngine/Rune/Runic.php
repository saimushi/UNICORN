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
 * @version    SVN: $Id: Runic.php,v 1.1 2009/10/07 06:02:25 syuhei_ono Exp $
 */

// {{{ Rune_Runic

/**
 * DOM parser base template engine.
 *
 * @package    Runemaster
 * @copyright  2008 KUMAKURA Yousuke All rights reserved.
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License (revised)
 * @version    Release: @package_version@
 */
class Rune_Runic
{

    // {{{ properties

    /**#@+
     * @access public
     */

    /**#@-*/

    /**#@+
     * @access protected
     */

    protected $_templateName;
    protected $_templateDirectory;
    protected $_templateSuffix;

    protected $_parameters = array();

    protected $_spellObjects = array();
    protected $_spellMethods = array();

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
     * @return void
     */
    public function __construct() {}

    // }}}
    // {{{ setTemplateDirectory

    /**
     * Sets template directory.
     * 
     * @param string $templateDirectory
     * @return void
     */
    public function setTemplateDirectory($templateDirectory)
    {
        $this->_templateDirectory = $templateDirectory;
    }

    // }}}
    // {{{ getTemplateDirectory

    /**
     * Gets template directory.
     * 
     * @return string
     */
    public function getTemplateDirectory()
    {
        return $this->_templateDirectory;
    }

    // }}}
    // {{{ setTemplate

    /**
     * Sets template name.
     * 
     * @param string $templateName
     * @return void
     */
    public function setTemplate($templateName)
    {
        $this->_templateName = $templateName;
    }

    // }}}
    // {{{ setTemplateSuffix

    /**
     * Sets template file suffix.
     * 
     * @param string $templateSuffix
     * @return void
     */
    public function setTemplateSuffix($templateSuffix)
    {
        $this->_templateSuffix = $templateSuffix;
    }

    // }}}
    // {{{ getTemplateSuffix

    /**
     * Gets template file suffix.
     * 
     * @return string
     */
    public function getTemplateSuffix()
    {
        return $this->_templateSuffix;
    }

    // }}}
    // {{{ setParameter

    /**
     * Sets runic parameter by namespace and name.
     * 
     * @param string $space
     * @param string $name
     * @param mixed  $parameter
     * @return void
     */
    public function setParameter($space, $name, $parameter)
    {
        if (!isset($this->_parameters[$space])) {
            $this->_parameters[$space] = array();
        }

        $this->_parameters[$space][$name] = $parameter;
    }

    // }}}
    // {{{ getParameter

    /**
     * Gets runic parameter by namespace and name.
     * 
     * @param string $space
     * @param string $name
     * @return mixed
     */
    public function getParameter($space, $name = null)
    {
        if (!isset($this->_parameters[$space])) {
            return array();
        }

        if (is_null($name)) {
            return $this->_parameters[$space];
        }

        if (!isset($this->_parameters[$space][$name])) {
            return array();
        }

        return $this->_parameters[$space][$name];
    }

    // }}}
    // {{{ getSpellMethods

    /**
     * Gets rune spell methods.
     * 
     * @return mixed
     */
    public function getSpellMethods()
    {
        return $this->_spellMethods;
    }

    // }}}
    // {{{ getSpell

    /**
     * Gets rune spell object.
     * 
     * @param string $spellName
     * @return mixed
     */
    public function &getSpell($spellName)
    {
        return $this->_spellObjects[$spellName];
    }

    // }}}
    // {{{ getSpells

    /**
     * Gets rune spell list.
     * 
     * @return mixed
     */
    public function getSpells()
    {
        return $this->_spellObjects;
    }

    // }}}
    // {{{ addSpell

    /**
     * Adds new spell.
     * 
     * @param string $name
     * @param object $spell
     * @return void
     */
    public function addSpell($name, &$spell)
    {
        $this->_spellObjects[$name] = &$spell;
    }

    // }}}
    // {{{ addSpellMethod

    /**
     * Adds new spell method.
     * 
     * @param string $frontMethodName
     * @param string $spellName
     * @param string $spellMethod
     * @return void
     */
    public function addSpellMethod($frontMethodName, $spellName, $spellMethod)
    {
        $frontMethodName = strtolower($frontMethodName);

        $method = new stdClass();
        $method->name = $spellName;
        $method->method = $spellMethod;

        $this->_spellMethods[$frontMethodName] = &$method;
    }

    // }}}
    // {{{ clean

    /**
     * clean up the runic.
     * 
     * @return void
     */
    public function clean()
    {
        $this->_parameters = array();
        $this->_spellObjects = array();
        $this->_spellMethods = array();
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
