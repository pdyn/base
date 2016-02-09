<?php
/*
This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

/**
 * @copyright 2010 onwards James McQuillan (http://pdyn.net)
 * @author James McQuillan <james@pdyn.net>
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace pdyn\base\tests;

/**
 * Test utility functions.
 *
 * @group pdyn
 * @group pdyn_base
 * @codeCoverageIgnore
 */
class UtilsTest extends \PHPUnit_Framework_TestCase {

	/**
	 * Test genRandomStr function.
	 */
	public function test_genRandomStr() {
		$lengths = [2, 4, 8, 16, 32, 64, 128];
		foreach ($lengths as $len) {
			$str = \pdyn\base\Utils::genRandomStr($len);
			$this->assertInternalType('string', $str);
			$this->assertEquals($len, mb_strlen($str));
		}
	}

	/**
	 * Test is_cli_env function.
	 */
	public function test_isclienv() {
		$this->assertTrue(\pdyn\base\Utils::is_cli_env());
	}
}
