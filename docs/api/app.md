# `class Socarrat\Serve\App`

The application.

| Public property name | Type                    | Description             |
|----------------------|-------------------------|-------------------------|
| `$router`            | [`Router`](./router.md) | The application router. |
| `$serverTiming`      | `bool`                  | Whether to send the Server-Timing header for testing purposes. If true, a Server-Timing header will be sent with each request, e.g. `Server-Timing: exec;dur=2.775166`. |
| `$poweredBy`         | `string`                | The value of the `X-Powered-By` header. If you set this to `""`, no custom X-Powered-By will be sent: instead something like `PHP/8.2.0`. |

## `public function run()`

Run the app.

Just before an HTTP response has been sent, `App::$hrtStop` is set to calculate the execution time using `App::$hrtStart`.

## `public function getExecTime(): float`

Returns the time in milliseconds that the application took to send a response. This value is calculated by subtracting `App::$hrtStop` from `App::$hrtStart`.

Returns the xecution time in milliseconds, or -1 if the execution has not yet terminated.
