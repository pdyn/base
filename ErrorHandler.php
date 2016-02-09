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
 * Collection of error handling functions.
 */
class ErrorHandler {

	/**
	 * Handle uncaught exceptions.
	 *
	 * @param \Exception $exception An uncaught exception.
	 */
	public static function exception_handler(\Exception $exception) {
		global $CFG, $USR;
		$errcode = $exception->getCode();
		$errmsg = $exception->getMessage();
		$errcodelabel = ($exception instanceof \pdyn\base\Exception)
			? $exception->get_string_from_code($errcode)
			: static::getinternalerrorlabel($errcode);
		if (\pdyn\base\Utils::is_cli_env() === true) {
			echo 'APP ERROR: '.$errcodelabel.': '.$errmsg."\n";
		} else {
			// Atlas exceptions' error codes are HTTP status codes, so send one.
			if (($exception instanceof \pdyn\base\Exception) && !empty($errcode) && !headers_sent()) {
				\pdyn\httputils\Utils::send_status_code($errcode, '', false);
			} else {
				\pdyn\httputils\Utils::send_status_code(500, '', false);
			}
			$LOG = new \pdyn\log\Logger($CFG->log_general);
			$LOG->error($errcodelabel.': '.$exception);
			if (isset($_GET['ajax'])) {
				header('Content-type: application/json');
				echo json_encode(['result' => 'fail', 'msg' => $errcodelabel.': '.$errmsg]);
			} else {
				$debugmode = (!empty($CFG) && (bool)$CFG->get('core', 'debugmode', false) === true) ? true : false;
				$isadmin = (!empty($USR) && $USR->is_admin === true) ? true : false;
				$backtrace = ($isadmin === true || $debugmode === true)	? static::format_backtrace($exception->getTrace()) : null;
				static::write_error_html($errcodelabel, $errmsg, $errcode, $backtrace);
			}
		}
		die();
	}

	public static function getinternalerrorlabel($errcode) {
		$errlang = [
			E_ERROR => 'Error',
			E_WARNING => 'Warning',
			E_PARSE => 'Parse Error',
			E_NOTICE => 'Notice',
			E_CORE_ERROR => 'Core Error',
			E_CORE_WARNING => 'Core Warning',
			E_COMPILE_ERROR => 'Compile Error',
			E_COMPILE_WARNING => 'Compile Warning',
			E_USER_ERROR => 'Error',
			E_USER_WARNING => 'Warning',
			E_USER_NOTICE => 'Notice',
			E_STRICT => 'Strict Standards',
			E_RECOVERABLE_ERROR => 'Recoverable Error',
			E_DEPRECATED => 'Deprecated',
			E_USER_DEPRECATED => 'Deprecated'
		];
		return (isset($errlang[$errcode])) ? $errlang[$errcode] : $errcode;
	}

	/**
	 * Format a backtrace for better display.
	 *
	 * @param array $backtrace A backtrace from debug_backtrace.
	 * @return string Html that better displays the backtrace.
	 */
	public static function format_backtrace($backtrace) {
		global $CFG;
		$html = '';
		$i = count($backtrace);
		foreach ($backtrace as $trace) {
			$file = (isset($trace['file'])) ? str_replace($CFG->base_absroot, '', $trace['file']) : 'unknown';
			$line = 'Line: '. ((isset($trace['line'])) ? $trace['line'] : '-');
			$func = ((isset($trace['function'])) ? $trace['function'] : '');
			ini_set('html_errors', 0);
			$argstr = array();
			if (!empty($trace['args']) && is_array($trace['args'])) {
				foreach ($trace['args'] as $arg) {
					ob_start();
					var_dump($arg);
					$stringval = ob_get_contents();
					ob_end_clean();
					$argstr[] = $stringval;
				}
			}
			$args = implode(', ', $argstr);
			$func .= '('.$args.')';
			if (\pdyn\base\Utils::is_cli_env() === true) {
				$html .= $i."\t".$file."\t".$line."\t".$func."\n";
			} else {
				$html .= '<tr>';
					$html .= '<td style="vertical-align:top">'.$i.'</td>';
					$html .= '<td style="vertical-align:top">'.$file.'</td>';
					$html .= '<td style="vertical-align:top">'.$line.'</td>';
					$html .= '<td><pre style="margin:0;">'.$func.'</pre></td>';
				$html .= '</tr>';
			}
			$i--;
		}
		return (\pdyn\base\Utils::is_cli_env() === true)
			? "**********\n".$html."**********\n"
			: '<table cellspacing="5" style="color:inherit">'.$html.'</table>';
	}

	/**
	 * Handle PHP errors.
	 *
	 * @param int $errcode The error code.
	 * @param string $errstr The error description
	 * @param string $errfile The file the error occurred in.
	 * @param int $errline The line the error occurred on.
	 */
	public static function error_handler($errcode, $errstr, $errfile, $errline, $errcontext) {
		throw new \Exception($errstr.' in '.$errfile.' on line '.$errline, $errcode);
	}

	/**
	 * For handle-able errors, print out our custom error screen.
	 *
	 * @param string $errtitle The title of the error.
	 * @param string $errdetails Details of the error.
	 * @param string $errcode (Optional) An error code.
	 * @param string $backtrace (Optional) A backtrace.
	 */
	public static function write_error_html($errtitle, $errdetails, $errcode, $backtrace = null) {
		global $USR;
		?>
		<!DOCTYPE HTML>
		<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
			<head>
				<meta charset="UTF-8" />
				<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
				<title><?php echo $errtitle ?></title>
				<style>
					html {
						background-color: #111;
						font-family: sans-serif;
						color: #fff;
					}
					.errbox {
						margin: 3rem;
						padding: 1rem;
						border-radius: 0.5rem;
						background-color: #500;
						border: 1px solid #f00;
					}
					.errbox h1, .errbox h2, .errbox h4 {
						margin: 0;
						padding: 0;
					}
					.errbox h1 {
						text-align: center;
						margin: 1rem 1rem 2rem;
					}
					.errbox h4 {
						margin-bottom: 1rem;
					}
					.errbox > div {
						background-color: #300;
						border: 1px solid #800;
						padding: 1rem;
					}
				</style>
			</head>
			<body>
				<div id="page">
					<div id="subContent">
						<div class="errbox">
							<?php
								echo '<h1>'.$errcode.': '.$errtitle.'</h1>';
								if (!empty($errdetails)) {
									echo '<div><h2>'.$errdetails.'</h2></div>';
								}
								if (!empty($USR) && $USR->is_admin === true && !empty($backtrace)) {
									echo '<div><h4>Backtrace:</h4>'.$backtrace.'</div>';
								}
							?>
						</div>
					</div>
				</div>
			</body>
		</html>
		<?php
	}
}
