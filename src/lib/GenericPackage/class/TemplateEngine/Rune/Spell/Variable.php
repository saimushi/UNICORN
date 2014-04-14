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
 * @version    SVN: $Id: Variable.php,v 1.1 2009/10/07 06:02:28 syuhei_ono Exp $
 */

require_once 'Rune/Spell/Common.php';
require_once 'Rune/Spell/Variable/Controller.php';

// {{{ Rune_Spell_Variable

/**
 * DOM parser base template engine.
 *
 * @package    Runemaster
 * @copyright  2008 KUMAKURA Yousuke All rights reserved.
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License (revised)
 * @version    Release: @package_version@
 */
class Rune_Spell_Variable extends Rune_Spell_Common
{

    // {{{ properties

    /**#@+
     * @access public
     */

    /**#@-*/

    /**#@+
     * @access protected
     */

    protected $_variableKey = 'id';

    /**#@-*/

    /**#@+
     * @access private
     */

    private $_isAssigned = false;

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
        $this->_controller = new Rune_Spell_Variable_Controller($this->_runic, $this);

        foreach (Rune_Master::findAll($stone) as $node) {
            if (isset($node->isOutput) && $node->isOutput === false) {
                foreach ($node->children as $children) {
                    $children->isOutput = false;
                }
                continue;
            }
            $this->_replace($node);
        }

        if ($this->_isAssigned === true) {
            $stone->refresh();
        }
    }

    // }}}
    // {{{ getPropertyValue

    /**
     * getPropertyValue
     *
     * @param string $property
     * @return mixed
     */
    public function getPropertyValue($property)
    {
        $reversal = false;
        $result = null;

        if (preg_match('/^!(.+)$/', $property, $matches)) {
            $reversal = true;
            $property = $matches[1];
        }

        $property = trim($property);

        if (is_numeric($property)
            || preg_match('/^[\'\"](.*)[\'\"]$/', $property, $matches)
            ) {
            return !$reversal ? $property : !$property;
        }

        if ($this->_isFunction($property)) {
            $result = $this->_executeFunction($property);
            return !$reversal ? $result : !$result;
        }

        if ($this->_isExpression($property)) {
            $result = $this->_evaluateExpression($property);
            return !$reversal ? $result : !$result;
        }


        $names = explode('.', $property);
        $value = $this->getParameter('variables');

        while (($name = array_shift($names)) !== null) {

            if (preg_match('/^([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)((?:\[[0-9]+\])+)$/', $name, $matches)) {

                if (is_array($value) && isset($value[$matches[1]])) {
                    $value = $value[$matches[1]];
                } elseif (is_object($value) && isset($value->{$matches[1]})) {
                    $value = $value->{$matches[1]};
                } else {
                    $value = null;
                    break;
                }

                preg_match_all('/\[([0-9]+)\]/', $matches[2], $arrayKeys);
                foreach ($arrayKeys[1] as $arrayKey) {
                    if (isset($value[$arrayKey])) {
                        $value = $value[$arrayKey];
                    } else {
                        $value = null;
                        break;
                    }
                }

            } elseif (is_array($value)) {
                if (!isset($value[$name])) {
                    $value = null;
                    break;
                }

                $value = $value[$name];
            } elseif (is_object($value)) {

                if (preg_match('/^([^\(\)]+)\(([^\)]*)\)$/', $name, $matches)) {
                    $method = $matches[1];
                    $params = $this->_createParameters($matches[2]);

                    $value = call_user_func_array(array($value, $method), $params);
                } else {

                    if (!isset($value->$name)) {
                        $value = null;
                        break;
                    }

                    $value = $value->$name;
                }
            }
        }

        return !$reversal ? $value : !$value;
    }

    // }}}
    // {{{ assign

    /**
     * assign
     *
     * @param mixed $variables
     * @return void
     */
    public function assign($variables)
    {
        if (!is_object($variables) && !is_array($variables)) {
            return false;
        }

        $this->setParameter('variables', (object)$variables);
    }

    // }}}
    // {{{ setVariableKey

    /**
     * setVariableKey
     *
     * @param string $key
     * @return void
     */
    public function setVariableKey($key)
    {
        $this->setParameter('variableKey', $key);
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
        $this->setParameter('variableKey', $this->_variableKey);
        $this->addMethod('assign', 'assign');
        $this->addMethod('setVariableKey', 'setVariableKey');
    }

    /**#@-*/

    /**#@+
     * @access private
     */

    // }}}
    // {{{ _replace

    /**
     * _replace
     *
     * @param  object $node
     * @return void
     */
    private function _replace(&$node)
    {
        $key = $this->getParameter('variableKey');

        switch ($node->nodetype) {
        case HDOM_TYPE_ELEMENT:

            foreach ($node->attr as $name => $attribute) {
                switch ($name) {
                case 'foreach':
                    if ($this->_controller->createLoop($node)) {
                        $this->_isAssigned = true;
                    }
                    break;
                case 'if':
                    if ($this->_controller->adjustIfRule($node)) {
                        $this->_isAssigned = true;
                    }
                    break;
                case $key:

                    $this->_setVariableValueByAttribute($node, $key);
                    break;
                }

                if (preg_match_all('/{([a-zA-Z_\x7f-\xff][a-zA-Z0-9.\[\]_\|\x7f-\xff]*)}/',
                                   $attribute, $matchesList, PREG_SET_ORDER)
                    ) {
                    foreach ($matchesList as $matches) {
                        $value = $this->_getBracketValue($matches[1]);
                        $node->$name = str_replace($matches[0], $value, $node->$name);
                        $this->_isAssigned = true;
                    }
                }
            }

            break;

        case HDOM_TYPE_TEXT:
            if (preg_match_all('/{([a-zA-Z_\x7f-\xff][a-zA-Z0-9.\[\]_\|\x7f-\xff]*)}/',
                               $node->outertext, $matchesList, PREG_SET_ORDER)
                ) {
                foreach ($matchesList as $matches) {
                    $this->_setVariableValueByBracket($node, $matches[1]);
                }
            }
            break;
        }
    }

    // }}}
    // {{{ _setVariableValueByAttribute

    /**
     * _setVariablevalueByAttribute
     *
     * @param  object $node
     * @param  string $key
     * @return void
     */
    private function _setVariableValueByAttribute(&$node, $key)
    {
        $propertyName = $node->$key;
        $value = $this->getPropertyValue($propertyName);

        if (is_null($value)) {
            return false;
        }

        if (!isset($node->html) || !$node->html) {
            $value = htmlspecialchars($value, ENT_QUOTES);
        }

        if (isset($node->omitter) && $node->omitter) {
            $node->outertext = $value;
        } else {
            $node->innertext = $value;
        }

        $this->_isAssigned = true;
    }

    // }}}
    // {{{ _setVariablevalueByBracket

    /**
     * _setVariablevalueByBracket
     *
     * @param  object $node
     * @param  string $property
     * @return void
     */
    private function _setVariableValueByBracket(&$node, $property)
    {
        $value = $this->_getBracketValue($property);
        $property = preg_replace('/\|/', '\|', $property);
        $node->outertext = preg_replace("/\{{$property}\}/", $value,
                                        $node->outertext
                                        );
        $this->_isAssigned = true;
    }

    // }}}
    // {{{ _getBracketValue

    /**
     * _getBracketValue
     *
     * @param  string $property
     * @return mixed
     */
    private function _getBracketValue($property)
    {
        $names = explode('|', $property);
        $name = array_shift($names);

        $value = $this->getPropertyValue($name);
        if (is_null($value)) {
            return;
        }

        if (!in_array('html', $names)) {
            $value = htmlspecialchars($value, ENT_QUOTES);
        }

        return $value;
    }

    // }}}
    // {{{ _isFunction

    /**
     * _isFunction
     *
     * @param string $value
     * @return boolean
     */
    private function _isFunction($value)
    {
        if ($this->_parseFunction($value) === false) {
            return false;
        }

        return true;
    }

    // }}}
    // {{{ _isExpression

    /**
     * _isExpression
     *
     * @param string $value
     * @return boolean
     */
    private function _isExpression($value)
    {
        if (count($this->_parseExpression($value)) < 2) {
            return false;
        }

        return true;
    }

    // }}}
    // {{{ _parseFunction

    /**
     * _parseFunction
     *
     * @param $value
     * @return mixed
     */
    private function _parseFunction($value)
    {
        if (!preg_match('/^([^\(\)]+)\((.*)\)$/', $value, $matches)) {
            return false;
        }
        if (!function_exists($matches[1])) {
            return false;
        }

        $result = new stdClass();
        $result->name = $matches[1];
        $result->parameters = $matches[2];

        return $result;
    }

    // }}}
    // {{{ _executeFunction

    /**
     * _executeFunction
     *
     * @param string $value
     * @return mixed
     */
    private function _executeFunction($value)
    {
        $function = $this->_parseFunction($value);
        if ($function === false) {
            return null;
        }

        $params = $this->_createParameters($function->parameters);

        return call_user_func_array($function->name, $params);
    }

    // }}}
    // {{{ _createParameters()

    /**
     * _createParameters
     *
     * @param string $value
     * @return array
     */
    private function _createParameters($value)
    {
        $results = array();
        $parameters = explode(',', str_replace(' ', '', $value));

        foreach ($parameters as $parameter) {
            if (is_numeric($parameter)) {
                array_push($results, $parameter);
            } elseif (preg_match('/^[\'\"](.*)[\'\"]$/', $parameter, $matches)) {
                array_push($results, $matches[1]);
            } else {
                array_push($results, $this->getPropertyValue($parameter));
            }
        }

        return $results;
    }

    // }}}
    // {{{ _parseExpression

    /**
     * _parseExpression
     *
     * @param $value
     * @return mixed
     */
    private function _parseExpression($value)
    {
        $pattern = '( and | or | xor |\&\&|\|\||===|==|\!=|<>|\!==|<|>|<=|>=)';
        return preg_split("/{$pattern}/", $value, -1, PREG_SPLIT_DELIM_CAPTURE);
    }

    // }}}
    // {{{ _evaluateExpression

    /**
     * _evaluateExpression
     *
     * @param string $value
     * @return mixed
     */
    private function _evaluateExpression($value)
    {
        if (preg_match('/^\((.+)\)$/', $value, $matches)) {
            $value = $matches[1];
        }

        $parameters = $this->_parseExpression($value);

        $expression = '';
        $pattern = ' and | or | xor |\&\&|\|\||===|==|\!=|<>|\!==|<|>|<=|>=';

        foreach ($parameters as $parameter) {
            $parameter = trim($parameter);

            if (preg_match("/^{$pattern}$/", $parameter)) {
                $expression .= $parameter;
            } else {
                $variable = $this->getPropertyValue($parameter);
                if (is_numeric($variable)
                    || preg_match('/^[\'\"](.*)[\'\"]$/', $variable)
                    ) {
                    $expression .= $variable;
                } else {
                    $expression .= "'{$variable}'";
                }
            }
        }

        $rule = "return {$expression} ? true : false;";
        return eval($rule);
    }

    // }}}
    // {{{ _escapeScript

    /**
     * _escapeScript
     *
     * @param string $value
     * @return string
     */
    private function _escapeScript($value)
    {
        $patterns = array("!'!", '!"!', '!/!', '!>!', "!\\\!");
        $replaces = array("\'", '\"', '\/', '\x3e', '\\\\');
        return preg_replace($patterns, $replaces, $value);
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
