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

	/**
	 * Test uniqid function.
	 */
	public function test_uniqid() {
		//test no parameters
		$one = \pdyn\base\Utils::uniqid();
		$two = \pdyn\base\Utils::uniqid();
		$this->assertInternalType('string', $one);
		$this->assertInternalType('string', $two);
		$this->assertNotEquals($one, $two);

		$prefixes = ['', ''];
		$min = 13;
		foreach ($prefixes as $i => $prefix) {
			$lengths = [2, 3, 4, 6, 10, 12, 13, 14, 16, 32, 64, 128];
			foreach ($lengths as $j => $len) {

				if (!empty($prefix)) {
					$one = \pdyn\base\Utils::uniqid($len, $prefix);
					$two = \pdyn\base\Utils::uniqid($len, $prefix);
				} else {
					$one = \pdyn\base\Utils::uniqid($len);
					$two = \pdyn\base\Utils::uniqid($len);
				}

				$this->assertInternalType('string', $one, 'Failed with '.$prefix.'/'.$len);
				$this->assertInternalType('string', $two, 'Failed with '.$prefix.'/'.$len);

				// Encountered false failures when using $this->assertNotEquals
				$this->assertTrue(($one !== $two), 'Failed with '.$prefix.'/'.$len);

				if ($len < $min) {
					$len = $min;
				}

				$this->assertEquals($len, mb_strlen($one), 'Failed with '.$prefix.'/'.$len);
				$this->assertEquals($len, mb_strlen($two), 'Failed with '.$prefix.'/'.$len);
			}
		}
	}
}
