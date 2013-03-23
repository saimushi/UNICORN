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
 * @version    SVN: $Id: Stone.php,v 1.1 2009/10/07 06:02:25 syuhei_ono Exp $
 */

require_once dirname( __FILE__ ).'/imports/simple_html_dom.php';

// {{{ Rune_Stone

/**
 * DOM parser base template engine.
 *
 * @package    Runemaster
 * @copyright  2008 KUMAKURA Yousuke All rights reserved.
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License (revised)
 * @version    Release: @package_version@
 */
class Rune_Stone
{

	// {{{ properties

	/**#@+
	 * @access public
	 */
	 /**#@-*/
	 /**#@+
	 * @access protected
	 */

	protected $_dom;

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
	public function __construct()
	{
		$this->_dom = new simple_html_dom();
	}

	// }}}
	// {{{ setTemplate

	/**
	 * Sets template.
	 *
	 * @param string $template
	 * @param array $variables
	 * @return void
	 */
	public function setTemplate($template, $variables = null)
	{
		if (!file_exists($template)) {
			throw new Exception("Failed opening '$template': No such file.");
		}

		if (!is_null($variables)) {
			extract((array)$variables, EXTR_SKIP);
		}

		ob_start();
		include $template;
		$buffer = ob_get_contents();
		ob_end_clean();

		// XXX add start S.Ohno 2009/08/06 ファイルの保存文字ードから、internal_encodeに変換する処理を追加
		// まだ怪しい・・・
		if (mb_internal_encoding() != ($enc = mb_detect_encoding($buffer, "utf-8,sjis,euc-jp"))) {
			$buffer = mb_convert_encoding($buffer, mb_internal_encoding(), $enc);
		}
		// XXX add end

		$this->_dom->load($buffer);
	}

	// }}}
	// {{{ setContent

	/**
	 * Sets content.
	 *
	 * @param string $content
	 * @return void
	 */
	public function setContent($content)
	{
		$this->_dom->load($content);
	}

	// }}}
	// {{{ getDom

	/**
	 * Gets the DOM.
	 *
	 * @return object
	 */
	public function & getDom()
	{
		return $this->_dom;
	}

	// }}}
	// {{{ refresh

	/**
	 * Refresh the rune stone (DOM).
	 *
	 * @return void
	 */
	public function refresh()
	{
		$dom = new simple_html_dom();
		$dom->load($this->_dom->save());

		$this->_dom->clear();
		unset ($this->_dom);

		$this->_dom = & $dom;
	}

	// }}}
	// {{{ clear

	/**
	 * Clear the rune stone (DOM).
	 *
	 * @return void
	 */
	public function clear()
	{
		$this->_dom->clear();
		unset ($this);
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
