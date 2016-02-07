<?php
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
