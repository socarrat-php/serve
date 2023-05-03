<?php
/**
 * URI.php: Uniform Resource Identifier parsing
 *
 * @author Romein van Buren
 * @license MIT
 */

namespace Socarrat\Core;

/**
 * The URI class takes care of parsing Uniform Resource Identifiers.
 *
 * ## URI design
 *
 * ```
 * https://admin:welkom2018@mozilla.org:9090/view_page?page=about&action=edit#confirm
 * -----   ----- ---------- ----------- -------------- ---------- ----------- -------
 * |       |     |          |           |   |          |          |           |
 * |       |     |          |           |   |          |          |           $fragment
 * $scheme |     $password  $hostname   |   $path      |          $query["action"] = "edit"
 *         $username                    $port          $query["page"] = "about"
 * ```
 */
class URI {
	/**
	 * The URI scheme.
	 * @link https://en.wikipedia.org/wiki/Uniform_Resource_Identifier#Syntax
	 */
	public string $scheme = "http";

	/**
	 * The requested hostname.
	 * @link https://en.wikipedia.org/wiki/Host_(network)
	 * @link https://en.wikipedia.org/wiki/Uniform_Resource_Identifier#Syntax
	 */
	public string $hostname;

	/**
	 * The connection port.
	 * @link https://en.wikipedia.org/wiki/Port_(computer_networking)
	 * @link https://en.wikipedia.org/wiki/Uniform_Resource_Identifier#Syntax
	 */
	public int $port = 80;

	/**
	 * The user.
	 * @link https://en.wikipedia.org/wiki/Uniform_Resource_Identifier#Syntax
	 */
	public string $user = "";

	/**
	 * The password.
	 * @link https://en.wikipedia.org/wiki/Uniform_Resource_Identifier#Syntax
	 */
	public string $password = "";

	/**
	 * The path.
	 * @link https://en.wikipedia.org/wiki/Uniform_Resource_Identifier#Syntax
	 */
	public string $path = "/";

	/**
	 * Parsed querystring.
	 * @link https://en.wikipedia.org/wiki/Query_string
	 */
	public array $query = array();

	/**
	 * The URI fragment or hash.
	 * @link https://en.wikipedia.org/wiki/URI_fragment
	 */
	public string $fragment = "";

	/**
	 * Builds the URI of the current request using `$_SERVER`.
	 *
	 * Warning: this method depends on `$_SERVER["HTTP_HOST"]`, which is set by the client. Be careful.
	 *
	 * @return URI The URI object that corresponds with the request.
	 */
	public static function fromRequest(): URI {
		$uri = new static;
		$uri->scheme = @$_SERVER["HTTPS"] == "on" ? "https" : "http";
		$uri->hostname = parse_url($_SERVER["HTTP_HOST"] ?? "", PHP_URL_HOST);
		$uri->port = $_SERVER["SERVER_PORT"] ?? 80;

		$parsedUri = parse_url($_SERVER["REQUEST_URI"]);
		$uri->path = $parsedUri["path"] ?? "/";
		$uri->query = array();
		$uri->fragment = $parsedUri["fragment"] ?? "";

		if (($parsedUri["query"] ?? "") != "") {
			foreach (explode("&", $parsedUri["query"]) as $str) {
				$parts = explode("=", $str);
				$uri->query[array_shift($parts)] = implode("=", $parts);
			}
		}

		return $uri;
	}

	/**
	 * Stringify this URL instance.
	 */
	public function __toString(): string {
		$port = "";
		if (($this->scheme === "http") && ($this->port != 80)) {
			$port = ":".$this->port;
		}
		if (($this->scheme === "https") && ($this->port != 443)) {
			$port = ":".$this->port;
		}

		$credentials = "";
		if (($this->user ?? "") != "") {
			$credentials = $this->user;
			if (($this->password ?? "") != "") {
				$credentials .= ":".$this->password;
			}
			$credentials .= "@";
		}

		$query = "";
		if (sizeof($this->query) > 0) {
			$query = "?";
			$prependSep = false;
			foreach ($this->query as $key => $val) {
				if ($prependSep) {
					$query .= "&";
				} else {
					$prependSep = true;
				}

				// Append each query parameter.
				if (is_array($val)) {
					foreach ($val as $v) {
						$query .= $key."[]=".$v;
					}
				}
				else {
					$query .= $key."=".$val;
				}
			}
		}

		$fragment = "";
		if (($this->fragment ?? "") != "") {
			$fragment = "#".$this->fragment;
		}

        $string = $this->scheme."://".$credentials.$this->hostname.$port.$this->path.$query.$fragment;
		return $string;
	}
}
