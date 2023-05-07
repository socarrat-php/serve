# `class Socarrat\Serve\URI`

The URI class takes care of parsing Uniform Resource Identifiers.

| Public property name | Type     | Description                                                                         |
|----------------------|----------|-------------------------------------------------------------------------------------|
| `$scheme`            | `string` | The [URI scheme](https://en.wikipedia.org/wiki/Uniform_Resource_Identifier#Syntax). |
| `$hostname`          | `string` | The requested [hostname](https://en.wikipedia.org/wiki/Host_(network)).             |
| `$port`              | `int`    | The connection [port](https://en.wikipedia.org/wiki/Port_(computer_networking)).    |
| `$user`              | `string` | The user contained in the URI.                                                      |
| `$password`          | `string` | The user password contained in the URI.                                             |
| `$path`              | `string` | The path, including leading slash.                                                  |
| `$query`             | `array`  | Parsed [querystring](https://en.wikipedia.org/wiki/Query_string).                   |
| `$fragment`          | `string` | The [URI fragment](https://en.wikipedia.org/wiki/URI_fragment) (i.e. the hash).     |

See this schema for a dissection:

```
https://admin:welkom2020@hofvantwente.nl:9090/view_page?page=about&action=edit#confirm
-----   ----- ---------- --------------- ---- --------- ---------- ----------- -------
|       |     |          |               |    |         |          |           |
$scheme |     |          $hostname       $port|         |          |           $fragment
        |     $password                       |         |          $query["action"] = "edit"
        $username                             |         $query["page"] = "about"
		                                      $path (includes leading slash)
```

## `public static function fromRequest(): URI`

Builds the URI of the current request using `$_SERVER`, and returns a URI object.

Warning: this method depends on `$_SERVER["HTTP_HOST"]`, which is set by the client. Be careful.
