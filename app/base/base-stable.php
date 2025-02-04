<?php

session_start();

if (!function_exists('dump')) {
    function dump(...$vars)
    {
        foreach ($vars as $var) {
            echo "<pre>";
            var_dump($var);
            echo "</pre>";
        }
        exit;
    }
}

if (!function_exists('dv')) {
    function dv(...$data)
    {
        $style = '
            <style>
                .dump-container {
                    background-color: black;
                    color: white;
                    font-size: 0.7em;
                    padding: 10px;
                    border-radius: 5px;
                    margin: 10px 0;
                }
                .dump-container pre {
                    margin: 0;
                    overflow-x: auto;
                }
            </style>
        ';

        echo $style;

        echo '<div class="dump-container">';
        foreach ($data as $item) {
            echo '<strong>Type: </strong>' . gettype($item) . "<br>";
            echo '<pre>';
            print_r($item);
            echo '</pre>';
        }
        echo '</div>';
    }
}

if (!function_exists('dd')) {
    function dd(...$vars)
    {
        echo "<style>
            .dd-debug {
                background-color: #2e2e2e;
                color: #f0f0f0;
                padding: 20px;
                border: 1px solid #444;
                border-radius: 5px;
                max-height: auto;
                font-family: monospace;
                overflow: hidden;
                word-wrap: break-word;
                white-space: normal;
            }

            .dd-debug pre {
                margin: 0;
                color: #f0f0f0;
                white-space: pre-wrap;
            }

            .dd-debug .object-properties {
                margin: 5px 0;
            }

            .dd-debug .object-values {
                margin-left: 20px;
            }

            .dd-debug .title {
                font-size: 1.5em;
                margin-bottom: 10px;
            }
        </style>";

        echo "<div class='dd-debug'><div class='title'>Vignt - Dump data and Die the code</div>";
        foreach ($vars as $var) {
            echo "<div class='dd-debug'>";
            echo "<div class='type'>Type: " . gettype($var) . "</div>";

            if (is_string($var)) {
                $length = strlen($var);
                echo "<div class='object-properties'>Length: $length</div>";
                echo "<div class='object-properties'>Content:</div>";
                echo "<pre>" . htmlspecialchars($var) . "</pre>";
            } elseif (is_object($var)) {
                echo "<div class='object-properties'>Object of class " . get_class($var) . "</div>";
                echo "<pre>";
                foreach (get_object_vars($var) as $property => $value) {
                    echo "<div class='object-values'>$property => ";
                    if (is_object($value)) {
                        $temp = $value;
                        echo "<pre>";
                        echo "<div class='object-values'>";
                        foreach ($temp as $key => $zz) {
                            echo "$key => ";
                            if (is_object($zz)) {
                                echo "[\n";
                                foreach ($zz as $subKey => $subzz) {
                                    echo "    $subKey => ";
                                    if (is_array($subzz) || is_object($subzz)) {
                                        echo "<pre>" . htmlspecialchars(print_r($subzz, true)) . "</pre>";
                                    } else {
                                        echo htmlspecialchars($subzz);
                                    }
                                    echo "\n";
                                }
                                echo "]\n";
                            } else {
                                echo htmlspecialchars($zz);
                            }
                            echo "\n";
                        }

                        echo "</div>";
                        echo "</pre>";
                    } elseif (is_array($value)) {
                        echo "[\n";
                        foreach ($value as $subKey => $subValue) {
                            echo "    '$subKey' => ";
                            if (is_array($subValue) || is_object($subValue)) {
                                echo "<pre>" . htmlspecialchars(print_r($subValue, true)) . "</pre>";
                            } else {
                                echo htmlspecialchars($subValue);
                            }
                            echo "\n";
                        }
                        echo "]\n";
                    } else {
                        echo htmlspecialchars($value);
                    }
                    echo "</div>";
                }
                echo "</pre>";
            } elseif (is_array($var)) {
                echo "<pre>";
                foreach ($var as $key => $value) {
                    echo "[$key] => ";
                    if (is_object($value)) {
                        $temp = $value;
                        echo "object <pre>";
                        echo "<div class='object-values'>";
                        foreach ($temp as $key => $zz) {
                            echo "$key => ";
                            if (is_object($zz)) {
                                echo "[\n";
                                foreach ($zz as $subKey => $subzz) {
                                    echo "    $subKey => ";
                                    if (is_array($subzz) || is_object($subzz)) {
                                        echo "<pre>" . htmlspecialchars(print_r($subzz, true)) . "</pre>";
                                    } else {
                                        echo htmlspecialchars($subzz);
                                    }
                                    echo "\n";
                                }
                                echo "]\n";
                            } else {
                                echo htmlspecialchars($zz);
                            }
                            echo "\n";
                        }

                        echo "</div>";
                        echo "</pre>";
                    } elseif (is_array($value)) {
                        echo "[\n";
                        foreach ($value as $subKey => $subValue) {
                            echo "    '$subKey' => ";
                            if (is_array($subValue) || is_object($subValue)) {
                                echo "<pre>" . htmlspecialchars(print_r($subValue, true)) . "</pre>";
                            } else {
                                echo htmlspecialchars($subValue);
                            }
                            echo "\n";
                        }
                        echo "]\n";
                    } else {
                        echo htmlspecialchars($value);
                    }
                    echo "\n";
                }
                echo "</pre>";
            } elseif (is_object($var)) {
                echo "<pre>";
                foreach ($var as $key => $value) {
                    echo "$key => ";
                    if (is_object($value)) {
                        echo "[\n";
                        foreach ($value as $subKey => $subValue) {
                            echo "    $subKey => ";
                            if (is_array($subValue) || is_object($subValue)) {
                                echo "<pre>" . htmlspecialchars(print_r($subValue, true)) . "</pre>";
                            } else {
                                echo htmlspecialchars($subValue);
                            }
                            echo "\n";
                        }
                        echo "]\n";
                    } else {
                        echo htmlspecialchars($value);
                    }
                    echo "\n";
                }
                echo "</pre>";
            } else {
                echo "<pre>";
                var_dump($var);
                echo "</pre>";
            }
            echo "</div>";
        }
        exit;
    }
}

function baseconfig($key = null)
{
    static $config = null;

    if ($config === null) {
        $file = '.baseconfig';
        if (!file_exists($file)) {
            throw new Exception("Config file does not exist.");
        }

        $json = file_get_contents($file);
        $config = json_decode($json, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception("Invalid JSON in config file.");
        }
    }

    if ($key === null) {
        return $config;
    }

    $keys = explode('.', $key);
    $value = $config;

    foreach ($keys as $k) {
        if (!isset($value[$k])) {
            return null;
        }
        $value = $value[$k];
    }

    return $value;
}

function createTemporaryFile($filePath)
{
    $content = file_get_contents($filePath);
    
    $a = "<?php include 'resource/view/base/layout.vignt.php' ?>";
    $content = $a . $content;
    
    $patterns = [
        '/!@foreach \((.*?)\) @!/' => '<?php foreach($1) { ?>',
        '/!@endforeach/' => '<?php } ?>',
        '/!@if \((.*?)\) @!/' => '<?php if($1) { ?>',
        '/!@endif/' => '<?php } ?>',
        '/!@else/' => '<?php } else { ?>',
        '/!@for \((.*?)\) @!/' => '<?php for($1) { ?>',
        '/!@endfor/' => '<?php } ?>',
        '/\{\!\s*(.*?)\s*\!\}/' => '<?php echo $1 ?>',
        '/!@build \((.*?)\)/' => '<?php require \'$1\' ?>',
    ];
    foreach ($patterns as $pattern => $replacement) {
        $content = preg_replace($pattern, $replacement, $content);
    }

    $tempFile = tempnam(sys_get_temp_dir(), 'temp_') . '.php';
    file_put_contents($tempFile, $content);

    return $tempFile;
}

global $middlewares;
$middlewares = [];

function middleware(array $middlewareList)
{
    global $middlewares;
    $originalMiddlewares = $middlewares;
    $middlewares = $middlewareList;

    return new class($originalMiddlewares)
    {
        private $originalMiddlewares;

        public function __construct($originalMiddlewares)
        {
            $this->originalMiddlewares = $originalMiddlewares;
        }

        public function group(callable $callback)
        {
            $callback();
            global $middlewares;
            $middlewares = $this->originalMiddlewares;
        }
    };
}

global $vignt_routes;
$vignt_routes = [];

function route($path)
{
    global $vignt_routes, $currentPrefix, $middlewares;
    $fullPath = rtrim($currentPrefix, '/') . '/' . ltrim($path, '/');
    $fullPath = preg_replace('/\/+/', '/', $fullPath);
    $pattern = str_replace(['{', '}'], ['<', '>'], $fullPath);

    return new class($pattern)
    {
        private $pattern;
        private $controller;
        private $function;
        private $method;
        private $middleware;

        public function __construct($pattern)
        {
            $this->pattern = $pattern;
        }

        public function controller($controller)
        {
            $this->controller = $controller;
            return $this;
        }

        public function function ($function)
        {
            $this->function = $function;
            return $this;
        }

        public function method($method)
        {
            global $vignt_routes;
            $this->method = $method;

            global $middlewares;
            $vignt_routes[$this->pattern] = [
                'controller' => $this->controller,
                'function' => $this->function,
                'method' => $this->method,
                'middleware' => $middlewares,
            ];

            return $this;
        }
    };
}

function callMiddleware(array $middlewareList)
{
    foreach ($middlewareList as $middlewareClass) {
        $middlewareClass = ucfirst($middlewareClass) . "Middleware";
        if (class_exists($middlewareClass) && is_subclass_of($middlewareClass, Middleware::class)) {
            $middleware = new $middlewareClass();
            $middleware->handle();
        } else {
            throw new Exception("Middleware class $middlewareClass does not exist or is not valid.");
        }
    }
}

function routePrefix($prefix, callable $callback)
{
    global $currentPrefix;
    $currentPrefix = '/' . trim($prefix, '/');
    $callback();
    $currentPrefix = '';
}

function routes($usr_ur)
{
    global $vignt_routes;
    $views = 'resource/view/';
    $aczzq = 0;
    $usr_ur = rtrim($usr_ur, '/');

    $requestMethod = $_SERVER['REQUEST_METHOD'];

    foreach ($vignt_routes as $pattern => $route) {
        $pattern = rtrim($pattern, '/');
        $patternParts = explode('/', $pattern);
        $urlParts = explode('/', $usr_ur);

        if (count($patternParts) !== count($urlParts)) {
            continue;
        }
        $args = [];
        $match = true;
        foreach ($patternParts as $index => $part) {
            if (strpos($part, '<') === 0 && strpos($part, '>') === strlen($part) - 1) {
                $args[] = $urlParts[$index];
            } elseif ($part !== $urlParts[$index]) {
                $match = false;
                break;
            }
        }

        if ($match) {
            if ($route['method'] != $_SERVER['REQUEST_METHOD']) {
                header("HTTP/1.0 403 Forbidden");
                $return = ['view' => '403'];
                $method = $route['method'];
                $filePath = $views . 'base/' . $return['view'] . '.vignt.php';
                $tempFile = createTemporaryFile($filePath);
                require_once $tempFile;
                unlink($tempFile);
                exit();
            }

            $dataRequest = new DataRequest();
            $args[] = $dataRequest;

            try {
                $controllerClass = $route['controller'];
                $functionName = $route['function'];
                $return = callMethodIfExists($controllerClass, $functionName, ...$args);

                if ($return) {
                    if (is_string($return) && strpos($return, 'view@') === 0) {
                        $tempRet = explode('@', $return);
                        if ($tempRet[0] == 'view') {
                            $tempV = str_replace('.', '/', $tempRet[1]);
                            $filePath = $views . $tempV . '.vignt.php';
                            $tempFile = createTemporaryFile($filePath);
                            include $tempFile;
                            unlink($tempFile);
                            $aczzq = 1;
                            exit();
                        }
                    }
                    echo json_encode($return);
                }
            } catch (Exception $e) {
                echo "Error: " . $e->getMessage();
            }

            $aczzq = 1;
            break;
        }
    }

    if ($aczzq != 1) {
        throw new \Exception("Route $usr_ur is not found in the configuration or does not have a correct return");
    }
}

function callMethodIfExists($className, $methodName, ...$args)
{
    if (!class_exists($className)) {
        throw new Exception("Class $className does not exist.");
    }
    $reflectionClass = new ReflectionClass($className);

    if (!$reflectionClass->hasMethod($methodName)) {
        throw new Exception("Method $methodName does not exist in class $className.");
    }

    $reflectionMethod = $reflectionClass->getMethod($methodName);
    $parameters = $reflectionMethod->getParameters();
    $dependencies = [];

    foreach ($parameters as $parameter) {
        $type = $parameter->getType();
        if ($type && !$type->isBuiltin()) {
            $dependencyClass = $type->getName();
            if (class_exists($dependencyClass)) {
                $dependencies[] = new $dependencyClass();
            } else {
                throw new Exception("Dependency class $dependencyClass does not exist.");
            }
        } else {
            $dependencies[] = array_shift($args);
        }
    }

    if ($reflectionMethod->isStatic()) {
        return $reflectionClass->getMethod($methodName)->invoke(null, ...$dependencies);
    } else {
        $instance = $reflectionClass->newInstance();
        return $reflectionClass->getMethod($methodName)->invoke($instance, ...$dependencies);
    }
}

class View
{
    private $viewName;
    private $data = [];

    public function __construct($viewName)
    {
        $this->viewName = $viewName;
    }

    public function data($data)
    {
        $this->data = $data;
        return $this;
    }

    public function render()
    {
        $views = 'resource/view/';
        $t_viewName = str_replace('.', '/', $this->viewName);
        $filePath = $views . $t_viewName . '.vignt.php';

        if (file_exists($filePath)) {
            extract($this->data);

            $tempFile = createTemporaryFile($filePath);
            require $tempFile;
            unlink($tempFile);
            exit();
        } else {
            echo "Error: View file does not exist.";
        }
    }
}

function view($viewName)
{
    return new View($viewName);
}

class JsonResponse
{
    private $data;

    public function __construct()
    {
        $this->data = null;
    }

    public function setData($data)
    {
        $this->data = $data;
        return $this;
    }

    public function make($data = null)
    {
        if ($data !== null) {
            $this->data = $data;
        }
        $jsonData = json_encode($this->data);
        header('Content-Type: application/json');
        return $jsonData;
    }
}

function json()
{
    return new JsonResponse();
}

class VigntQuery
{
    public function __construct(array $data)
    {
        foreach ($data as $key => $value) {
            $this->{$key} = $value;
        }
    }
}

class DataRequest
{
    private $queryParams;
    private $postParams;
    private $serverParams;
    private $headers;

    public function __construct()
    {
        $this->queryParams = $_GET;
        $this->postParams = json_decode(file_get_contents('php://input'), true) ?: $_POST;
        $this->serverParams = $_SERVER;
        $this->headers = $this->getHeaders();
    }

    public function all()
    {
        return array_merge($this->queryParams, $this->postParams);
    }

    public function get($key, $default = null)
    {
        return isset($this->queryParams[$key]) ? $this->queryParams[$key] : $default;
    }

    public function post($key, $default = null)
    {
        return isset($this->postParams[$key]) ? $this->postParams[$key] : $default;
    }

    public function server($key, $default = null)
    {
        return isset($this->serverParams[$key]) ? $this->serverParams[$key] : $default;
    }

    public function getHeaders()
    {
        $headers = [];
        foreach ($this->serverParams as $key => $value) {
            if (substr($key, 0, 5) === 'HTTP_') {
                $headerName = str_replace('_', ' ', substr($key, 5));
                $headerName = ucwords(strtolower($headerName));
                $headers[$headerName] = $value;
            }
        }
        return $headers;
    }

    public function header($key, $default = null)
    {
        $key = ucwords(strtolower($key));
        return isset($this->headers[$key]) ? $this->headers[$key] : $default;
    }

    public function __get($key)
    {
        if (isset($this->queryParams[$key])) {
            return $this->queryParams[$key];
        }

        if (isset($this->postParams[$key])) {
            return $this->postParams[$key];
        }

        return null;
    }
}

function vignt_url($urlzx)
{
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://';
    $base_url = $protocol . $_SERVER['HTTP_HOST'] . '/';
    echo $base_url . $urlzx;
}

function getCodeSnippet($file, $line)
{
    if (!file_exists($file)) {
        return "<p>Code snippet not available.</p>";
    }
    $lines = file($file);
    $start_e = rand(3, 7);
    $end_e = 10 - $start_e;
    $start = max(0, $line - $start_e);
    $end = min(count($lines) - 1, $line + $end_e);

    $snippet = array_slice($lines, $start, $end - $start + 1);
    $snippetLines = '';
    foreach ($snippet as $i => $codeLine) {
        $highlight = ($start + $i + 1) == $line ? 'highlight' : '';
        $snippetLines .= "<tr class='$highlight'><td>" . ($start + $i + 1) . "</td><td>" . htmlspecialchars($codeLine) . "</td></tr>";
    }

    return "<table><thead><tr><th>Line</th><th>Code</th></tr></thead><tbody>$snippetLines</tbody></table>";
}

function displayErrorTrace1($message, $file, $line, $trace)
{
    $traceData = [];
    foreach ($trace as $index => $frame) {
        if (isset($frame['file']) && isset($frame['line'])) {
            $traceData[] = [
                'index' => $index,
                'file' => $frame['file'],
                'line' => $frame['line'],
                'function' => $frame['function'] ?? 'unknown',
                'codeSnippet' => getCodeSnippet($frame['file'], $frame['line']),
            ];
        }
    }

    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Error Trace</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                line-height: 1;
                margin: 0;
                padding: 0;
                color: #333;
                background-color: #f4f4f4;
                font-size: 1rem;
            }
            .error-details {
                margin-bottom: 2rem;
                border-bottom: 2px solid #ddd;
            }
            .error-details .file {
                font-weight: bold;
                color: #e74c3c;
            }
            .error-details .snippet {
                background: #f5f5f5;
                border: 1px solid #ddd;
                padding: 1rem;
                border-radius: 0.25rem;
                overflow-x: auto;
                margin-top: 0.9rem;
                line-height: 2;
                font-size: 0.875rem;
            }
            .container {
                max-width: 90vw;
                margin: 2rem auto;
                padding: 2rem;
                background: #fff;
                box-shadow: 0 0 1rem rgba(0, 0, 0, 0.1);
                border-radius: 0.5rem;
                display: flex;
                flex-direction: column;
            }
            h1 {
                font-size: 2rem;
                border-bottom: 2px solid #ddd;
                padding-bottom: 0.5rem;
                margin-bottom: 0.7rem;
                color: #e74c3c;
            }
            .trace-container {
                display: flex;
                gap: 1rem;
                flex-wrap: wrap;
            }
            .trace-list, .code-snippet {
                flex: 1;
                min-width: 20rem;
                min-height: 25rem;
            }
            .trace-list {
                border-right: 2px solid #ddd;
                padding-right: 1rem;
            }
            .trace-item {
                padding: 1rem;
                margin-bottom: 1rem;
                border: 1px solid #ddd;
                border-radius: 0.5rem;
                cursor: pointer;
                background: #f9f9f9;
            }
            .trace-item:hover {
                background: #eee;
            }
            .code-snippet {
                padding: 1rem;
                background: #f9f9f9;
                border-radius: 0.5rem;
                max-height: 30vh;
                overflow-y: auto;
            }
            .code-snippet table {
                width: 100%;
                border-collapse: collapse;
            }
            .code-snippet th, .code-snippet td {
                padding: 0.5rem;
                border: 1px solid #ddd;
                text-align: left;
            }
            .code-snippet .highlight {
                background-color: #ffe6e6;
            }
            @media (max-width: 600px) {
                body {
                    font-size: 0.875rem;
                }
                .container {
                    padding: 1rem;
                }
                h1 {
                    font-size: 1.5rem;
                }
                .trace-item {
                    font-size: 0.875rem;
                }
                .code-snippet {
                    font-size: 0.75rem;
                    min-height: 20rem;
                }
            }
        </style>
    </head>
    <body>
        <div class="container">
            <h1>Error Trace</h1>
            <div class="error-details">
                <p><strong>Message:</strong> <?php echo htmlspecialchars($message); ?></p>
                <p class="file">File: <?php echo htmlspecialchars($file); ?></p>
                <p>Line: <?php echo htmlspecialchars($line); ?></p>
            </div>
            <div class="trace-container">
                <div class="trace-list">
                    <?php foreach ($traceData as $traceItem): ?>
                        <div class="trace-item" onclick="showSnippet(<?php echo $traceItem['index']; ?>)">
                            <p><strong>File:</strong> <?php echo htmlspecialchars($traceItem['file']); ?></p>
                            <p><strong>Line:</strong> <?php echo htmlspecialchars($traceItem['line']); ?></p>
                            <p><strong>Function:</strong> <?php echo htmlspecialchars($traceItem['function']); ?></p>
                        </div>
                    <?php endforeach;?>
                </div>
                <div class="code-snippet">
                    <?php foreach ($traceData as $traceItem): ?>
                        <div class="code-content" id="snippet-<?php echo $traceItem['index']; ?>" style="display: none;">
                            <?php echo $traceItem['codeSnippet']; ?>
                        </div>
                    <?php endforeach;?>
                </div>
            </div>
        </div>
        <script>

            document.getElementById('snippet-' + 0).style.display = 'block';
            function showSnippet(index) {
                document.querySelectorAll('.code-content').forEach(el => el.style.display = 'none');
                document.getElementById('snippet-' + index).style.display = 'block';
            }

        </script>
    </body>
    </html>
    <?php
}

function displayErrorTrace($message, $file, $line, $trace)
{
    $traceData = [];
    foreach ($trace as $index => $frame) {
        if (isset($frame['file']) && isset($frame['line'])) {
            $traceData[] = [
                'index' => $index,
                'file' => $frame['file'],
                'line' => $frame['line'],
                'function' => $frame['function'] ?? 'unknown',
                'codeSnippet' => getCodeSnippet($frame['file'], $frame['line']),
            ];
        }
    }

    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@200;600&display=swap" rel="stylesheet">
        <title>Error Trace</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                line-height: 1;
                margin: 0;
                padding: 0;
                color: #fff;
                background-color: #001f3f;
                font-size: 1rem;
                font-family: 'Nunito', sans-serif;
            }
            .error-details {
                margin-bottom: 2rem;
                border-bottom: 2px solid #ddd;
            }
            .error-details .file {
                font-weight: bold;
                color: #e74c3c;
            }
            .error-details .snippet {
                background: #002f5f;
                border: 1px solid #444;
                padding: 1rem;
                border-radius: 0.25rem;
                overflow-x: auto;
                margin-top: 0.9rem;
                line-height: 2;
                font-size: 0.875rem;
                color: #fff;
            }
            .container {
                max-width: 90vw;
                margin: auto;
                padding: 1rem;
                background: #001f3f;
                box-shadow: 0 0 1rem rgba(0, 0, 0, 0.3);
                border-radius: 0.5rem;
                display: flex;
                flex-direction: column;
            }
            h1 {
                font-size: 2rem;
                border-bottom: 2px solid #ddd;
                padding-bottom: 0.5rem;
                margin-bottom: 0.7rem;
                color: #e74c3c;
            }
            .trace-container {
                display: flex;
                gap: 1rem;
                flex-wrap: wrap;
            }
            .trace-list, .code-snippet {
                flex: 1;
                min-width: 20rem;
                min-height: 25rem;
            }
            .trace-list {
                border-right: 2px solid #ddd;
                padding-right: 1rem;
            }
            .trace-item {
                padding: 1rem;
                margin-bottom: 1rem;
                border: 1px solid #444;
                border-radius: 0.5rem;
                cursor: pointer;
                background: #002f5f;
            }
            .trace-item:hover {
                background: #004080;
            }
            .code-snippet {
                padding: 1rem;
                background: #002f5f;
                border-radius: 0.5rem;
                max-height: 30vh;
                overflow-y: auto;
            }
            .code-snippet table {
                width: 100%;
                border-collapse: collapse;
            }
            .code-snippet th, .code-snippet td {
                padding: 0.5rem;
                border: 1px solid #444;
                text-align: left;
            }
            .code-snippet .highlight {
                background-color: #990000;
            }
            @media (max-width: 600px) {
                body {
                    font-size: 0.875rem;
                }
                .container {
                    padding: 1rem;
                }
                h1 {
                    font-size: 1.5rem;
                }
                .trace-item {
                    font-size: 0.875rem;
                }
                .code-snippet {
                    font-size: 0.75rem;
                    min-height: 20rem;
                }
            }
        </style>
    </head>
    <body>
        <div class="container">
            <h1>Error Trace</h1>
            <div class="error-details">
                <p><strong>Message:</strong> <?php echo htmlspecialchars($message); ?></p>
                <p class="file">File: <?php echo htmlspecialchars($file); ?></p>
                <p>Line: <?php echo htmlspecialchars($line); ?></p>
            </div>
            <div class="trace-container">
                <div class="trace-list">
                    <?php foreach ($traceData as $traceItem): ?>
                        <div class="trace-item" onclick="showSnippet(<?php echo $traceItem['index']; ?>)">
                            <p><strong>File:</strong> <?php echo htmlspecialchars($traceItem['file']); ?></p>
                            <p><strong>Line:</strong> <?php echo htmlspecialchars($traceItem['line']); ?></p>
                            <p><strong>Function:</strong> <?php echo htmlspecialchars($traceItem['function']); ?></p>
                        </div>
                    <?php endforeach;?>
                </div>
                <div class="code-snippet">
                    <?php foreach ($traceData as $traceItem): ?>
                        <div class="code-content" id="snippet-<?php echo $traceItem['index']; ?>" style="display: none;">
                            <?php echo $traceItem['codeSnippet']; ?>
                        </div>
                    <?php endforeach;?>
                </div>
            </div>
        </div>
        <script>
            document.getElementById('snippet-' + 0).style.display = 'block';
            function showSnippet(index) {
                document.querySelectorAll('.code-content').forEach(el => el.style.display = 'none');
                document.getElementById('snippet-' + index).style.display = 'block';
            }
        </script>
    </body>
    </html>
    <?php
}

function customErrorHandler($errno, $errstr, $errfile, $errline)
{
    if (ob_get_level() > 0) {
        ob_clean(); 
    }
    header("HTTP/1.1 500 Internal Server Error");
    if (!(error_reporting() & $errno)) {
        return;
    }

    $message = "$errstr";
    displayErrorTrace($message, $errfile, $errline, debug_backtrace());
    exit();
}

function customExceptionHandler($exception)
{
    if (ob_get_level() > 0) {
        ob_clean();
    }
    header("HTTP/1.1 500 Internal Server Error");
    $message = $exception->getMessage();
    $file = $exception->getFile();
    $line = $exception->getLine();
    displayErrorTrace($message, $file, $line, $exception->getTrace());
    exit;
}
