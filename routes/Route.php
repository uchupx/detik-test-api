<?php
namespace Routes;

use App\Exception\InvalidBodyException;
use App\Exception\NotFoundException;
use App\Exception\PageNotFoundException;
use Routes\RouteInterface;

class Route implements RouteInterface
{
    protected static $parameter;
    protected static $routes;

    public function __construct()
    {

    }

    protected static function route(string $method, string $path, $callback)
    {
        self::$routes[$method . $path] = [
            "method"   => $method,
            "path"     => self::segmen($path),
            "callback" => $callback,
        ];
    }

    protected static function segmen(string $path)
    {
        if (preg_match('/{.*}/i', $path)) {
            $p = explode("/", $path);
            $q = explode('/', parse_url($_SERVER["REQUEST_URI"])["path"]);
            $d = array_search('{:segmen}', $p);
        }

        $path            = @preg_replace('/{.*}/i', @$q[$d], $path);
        self::$parameter = @$q[$d];
        return $path;
    }

    public static function get(string $path, string $callback)
    {
        self::route("GET", $path, $callback);
    }

    public static function post(string $path, string $callback)
    {
        self::route("POST", $path, $callback);
    }

    public static function delete(string $path, string $callback)
    {
        self::route("DELETE", $path, $callback);
    }

    public static function put(string $path, string $callback)
    {
        self::route("PUT", $path, $callback);
    }

    public static function run()
    {
        $status      = "success";
        $requestPath = parse_url($_SERVER["REQUEST_URI"])['path'];
        $method      = $_SERVER["REQUEST_METHOD"];
        $callback    = null;

        foreach (self::$routes as $route) {
            if (in_array($requestPath, [$route["path"], $route["path"] . "/"]) && $route["method"] === $method) {
                $callback = $route["callback"];
                break;
            }
        }

        if (isset($_POST['_method']) && $callback !== null && $route['method'] === $_POST['_method']) {
            $callback = $route["callback"];
        }

        if ($callback === null) {
            header("HTTP/1.0 404 Not Found");
            exit;
        }

        try {
            $result = call_user_func_array($callback, [
                [
                    // array_merge($_GET, $_POST),
                    self::$parameter,
                ],
            ]);
        } catch (NotFoundException $e) {
            header('HTTP/1.1 400 Bad Request');

            $status = "error";
            $result = "not found";
        } catch (InvalidBodyException $e) {
            header('HTTP/1.1 400 Bad Request');

            $status = "error";
            $result = "invalid body";
        } catch (PageNotFoundException $e) {
            header('HTTP/1.1 404 Not Found');
            return;
        } catch (\Exception $e) {
            header('HTTP/1.1 500 Internal Server Error');

            $status = "error";
            $result = "internal server error";
        }

        $res = array(
            "status" => $status,
            "data"   => $result,
        );

        echo json_encode($res);
    }
}
