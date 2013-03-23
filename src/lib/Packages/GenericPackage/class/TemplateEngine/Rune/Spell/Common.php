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
 * @version    SVN: $Id: Common.php,v 1.1 2009/10/07 06:02:28 syuhei_ono Exp $
 */

// {{{ Rune_Spell_Common

/**
 * DOM parser base template engine.
 *
 * @package    Runemaster
 * @copyright  2008 KUMAKURA Yousuke All rights reserved.
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License (revised)
 * @version    Release: @package_version@
 */
class Rune_Spell_Common
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
    protected $_name;

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
     * @return void
     */
    public function __construct(&$runic)
    {
        $this->_name = strtolower(get_class($this));

        $this->_runic = &$runic;
        $this->_runic->addSpell($this->_name, $this);
    }

    // }}}
    // {{{ getSpaceParameter

    /**
     * getSpaceParameter
     * 
     * @return mixed
     */
    public function getSpaceParameter($space, $name)
    {
        $parameters = $this->getSpaceParameters($space);
        if (array_key_exists($name, $parameters)) {
            return $parameters[$name];
        } else {
            return null;
        }
    }

    // }}}
    // {{{ getSpaceParameters

    /**
     * getParameters
     * 
     * @return mixed
     */
    public function getSpaceParameters($space = null)
    {
        if (is_null($space)) {
            $space = $this->_name;
        }

        return $this->_runic->getParameter($space);
    }

    // }}}
    // {{{ getParameter

    /**
     * getParameter
     * 
     * @return mixed
     */
    public function getParameter($name)
    {
        return $this->_runic->getParameter($this->_name, $name);
    }

    // }}}
    // {{{ getParameters

    /**
     * getParameter
     * 
     * @return mixed
     */
    public function getParameters()
    {
        return $this->_runic->getParameter($this->_name);
    }

    // }}}
    // {{{ setParameter

    /**
     * getParameter
     * 
     * @return mixed
     */
    public function setParameter($key, $value)
    {
        $this->_runic->setParameter($this->_name, $key, $value);
    }

    // }}}
    // {{{ addMethod

    /**
     * addMethod
     * 
     * @return mixed
     */
    protected function addMethod($frontend, $backend)
    {
        $this->_runic->addSpellMethod($frontend, $this->_name, $backend);
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
        $names = explode('_', $this->_name);
        
        $methodName = 'set' . array_pop($names);
        $executeMethod = 'setParameter';
        $this->addMethod($methodName, $executeMethod);
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
