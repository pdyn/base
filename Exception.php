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

/**
 * Wrapper around standard Exception class providing standardized error codes.
 */
class Exception extends \Exception {
	/** Error code. General, nonspecific error. */
	const ERR_GENERAL = 0;

	/** Error code. Invalid request received. */
	const ERR_BAD_REQUEST = 400;

	/** Error code. No authorization present. */
	const ERR_UNAUTHORIZED = 401;

	/** Error code. User is not authorized to perform the action. */
	const ERR_FORBIDDEN = 403;

	/** Error code. Internal error, probably a bug. */
	const ERR_INTERNAL_ERROR = 500;

	/** Error code. Something should have happened but didn't. Probably a bug. */
	const ERR_PRECONDITION_FAILED = 412;

	/** Error code. The requested resource was not found. */
	const ERR_RESOURCE_NOT_FOUND = 404;

	/** Error code. The resource, while found, is not valid in this context. */
	const ERR_INVALID_RESOURCE = 406;

	/** Error code. The requested method has not yet been implemented. */
	const ERR_NOT_IMPLEMENTED = 501;

	/** Error code. Feature is currently disabled. */
	const ERR_DISABLED = 503;

	/** Error code. Networking is currently disabled. */
	const ERR_NETWORKING_DISABLED = 503;

	/**
	 * Translate an error code into a human-readable string explaining the problem.
	 *
	 * @param int $code The error code.
	 * @return string The human-readable description.
	 */
	public function get_string_from_code($code) {
		$errors = [
			self::ERR_GENERAL => 'General Error',
			self::ERR_UNAUTHORIZED => 'Unauthorized',
			self::ERR_BAD_REQUEST => 'Bad Request',
			self::ERR_INTERNAL_ERROR => 'Internal Error',
			self::ERR_RESOURCE_NOT_FOUND => 'Resource Not Found',
			self::ERR_PRECONDITION_FAILED => 'Precondition Failed',
			self::ERR_NETWORKING_DISABLED => 'Networking is Disabled'
		];

		return (isset($errors[$code])) ? $errors[$code] : '';
	}
}
