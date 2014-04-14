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
 * @version    SVN: $Id: FormValue.php,v 1.1 2009/10/07 06:02:28 syuhei_ono Exp $
 */

require_once 'Rune/Stone.php';
require_once 'Rune/Spell/Common.php';

// {{{ Rune_Spell_FromValue

/**
 * DOM parser base template engine.
 *
 * @package    Runemaster
 * @copyright  2008 KUMAKURA Yousuke All rights reserved.
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License (revised)
 * @version    Release: @package_version@
 */
class Rune_Spell_FormValue extends Rune_Spell_Common
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
        $formValues = $this->getParameters();
        if (!$formValues) {
            return;
        }

        foreach ($formValues as $formName => $formValue) {

            if (!$formValue && !is_array($formValue) && !is_object($formValue)) {
                continue;
            }

            if ($formName) {
                $formNodes = Rune_Master::find($stone, "form[name={$formName}]");
                $formNode = @$formNodes[0];
            } else {
                $formNode = &$stone;
            }

            if(is_object($formNode)){
            	foreach ($formValue as $name => $baseValue) {
                    if (is_object($baseValue)) {
                        continue;
                    }

                    if (is_array($baseValue)) {
                        foreach ($baseValue as $value) {
                            $value = htmlspecialchars($value, ENT_QUOTES);

                            $selector = "input[name=\"{$name}[]\"]";
                            foreach (Rune_Master::find($formNode, $selector) as $node) {
                                if (strtolower($node->type) !== 'radio'
                                    && strtolower($node->type) !== 'checkbox'
                                    ) {
                                    continue;
                                }

                                if ((string)$node->value !== (string)$value) {
                                    continue;
                                }

                                $node->checked = 'checked';
                            }
                        }

                    } else {

                        $value = htmlspecialchars($baseValue, ENT_QUOTES);

                        $selector = '[name="' . $name . '"]';
                        foreach (Rune_Master::find($formNode, $selector) as $node) {
                            switch ($node->tag) {
                            case 'textarea':
                                $node->innertext = $value;
                                break;
                            case 'select':
                                    $selectStone = new Rune_Stone();
                                    $selectStone->setContent($node->innertext);

                                $target = 'option[value="' . $value . '"]';
                                foreach (Rune_Master::find($selectStone, $target) as $option) {
                                    $option->selected = 'selected';
                                }
                                $node->innertext = Rune_Master::scan($selectStone);
                                $selectStone->clear();
                                break;
                            default:
                                if (strtolower($node->type) === 'radio'
                                    || strtolower($node->type) === 'checkbox'
                                    ) {
                                    if ((string)$node->value === (string)$value) {
                                        $node->checked = 'checked';
                                    }
                                } else {
                                    $node->value = $value;
                                }
                                break;
                            }
                        }
                    }
                }
            }
        }
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
