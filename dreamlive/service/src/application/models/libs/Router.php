<?php
class Router
{
    private $_routes = array();

    public function execute()
    {
        try {
            $url = $_SERVER["REQUEST_URI"];

            $rewrite = false;

            foreach ($this->_routes as $route) {
                if (preg_match($route["pattern"], $url, $matches)) {
                    $controllerName = $route["controller"]. "Controller";
                    $actionName = $route["action"] . "Action";

                    array_shift($matches);

                    foreach($route["parameters"] as $key=>$parameter) {
                        $_GET[$parameter] = $matches[$key];
                    }

                    $rewrite = true;
                }
            }

            if (! $rewrite) {
                preg_match("/([^\/]+)\/([^\/?]+)/", $url, $matches);

                if (empty($matches)) {
                    throw new Exception(" method is not exists", - 1000);
                }

                list ($method, $controller, $action) = $matches;

                $controllerName = ucfirst($controller) . 'Controller';
                $actionName = $action . 'Action';
            }

            Context::add("controllerName", $controllerName);
            Context::add("actionName", $actionName);
            Context::add("method", "/".$method);

            $controller = new $controllerName();

            if (! method_exists($controller, $actionName)) {
                throw new Exception("$actionName method is not exists", - 1001);
            }

            $controller->$actionName();
        } catch (Exception $e) {
            //Util::log("kkk",array($e),'xxx' );
            header('Content-Type: application/json; charset=utf-8');
            header("Server: nginx/1.2.3");

            $result = array(
                "errno" => $e->getCode(),
                "errmsg" => $e->getMessage(),
                "consume" => Consume::getTime(),
                "time" => time()
            );

            if (! $result['errno']) {
                $result['errno'] = ERROR_SYS_UNKNOWN;
                $result['errmsg'] = Util::getError(ERROR_SYS_UNKNOWN);
            }

            $content = json_encode($result);

            Logger::warning($content, array(), $result["errno"]);

            print $content;
            exit();
        }
    }

    public function addRoute($pattern, $controller, $action, $parameters)
    {
        $this->_routes[] = array(
            "pattern" => $pattern,
            "controller" => $controller,
            "action" => $action,
            "parameters" => $parameters
        );
    }
}
?>
