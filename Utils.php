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

namespace pdyn\base;

class Utils {
	/**
	 * Determine if we are in PHP CLI or not.
	 *
	 * @return bool True if we are in CLI, false otherwise.
	 */
	public static function is_cli_env() {
		return (php_sapi_name() === 'cli') ? true : false;
	}

	/**
	 * Generate a random string of letters and numbers of a specific length.
	 *
	 * @param int $l The desired length of the string.
	 * @return string The generated string.
	 */
	public static function genRandomStr($l) {
		$p = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$s = '';
		for ($i = 0; $i < $l; $i++) {
			$s .= $p[mt_rand(0, (mb_strlen($p) - 1))];
		}
		return $s;
	}
}