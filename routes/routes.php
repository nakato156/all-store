<?php
namespace routes;

require("./routes/middlewares/middlewares.php");
use  routes\middlewares\Middleware as Middle;

class Router extends Middle{
	public $use = "";
	public $path_render = "./";
	public $header = "";
	protected $ntf = true;
	
	public function get($url, $header=null, array $middleware=null) {
		$route = $_SERVER['REQUEST_URI'];
		$route = strstr($route, '?') ? strstr($route, '?', true): $route;  
		$url =$this->use.$url;

		$pre_route = str_replace($this->use, '', $route);

		// obteniendo parametros
		$urlRule = preg_replace('/:([^\/]+)/', '(?<\1>[^/]+)', $url);
		$urlRule = str_replace('/', '\/', $urlRule);
	
		preg_match_all('/:([^\/]+)/', $url, $parameterNames);
		$params = preg_match('/^' . $urlRule . '\/*$/s', $route, $matches);

		// verificando la ruta y url
		if($url != $pre_route && !($params)){
			return;
		}
		if($_SERVER['REQUEST_METHOD'] != "GET"){
			http_response_code(403);
			return;
		}

		$this->ntf = false;

		if($middleware!=null){
			//ejecutando middleware
			$middle = new Middle();
			$middle->exec_middleware($pre_route, $middleware);
			$closure = $middleware[0];
			if ($params) {
				$parameters = array_intersect_key($matches, array_flip($parameterNames[1]));
				if($header){
					include_once($this->path_render."\\".$this->header);
				}
				return call_user_func_array($closure, $parameters);
			}		
		}
		return;
	}

	public function view($url, $view, $middleware=null){
		$res = $this->process_url($url);
		$pre_route = $res[0];
		$params = $res[1];
		$parameterNames = $res[2];

		// verificando la ruta y url
		if($url != $pre_route && !($params)){
			return;
		}
		else if($_SERVER['REQUEST_METHOD'] != "GET"){
			http_response_code(403);
			return;
		}

		if($middleware !=null && isset($middleware["middleware"])){
			//ejecutando middleware
			$middle = new Middle();
			$middle->exec_middleware($pre_route, $middleware);
		}
		$this->ntf = false;
		include_once($this->path_render."\\".$this->header);
		return include_once($this->path_render."\\".$view);
	}

	public function post($url, $functions=null){
		$res = $this->process_url($url);
		$pre_route = $res[0];
		$params = $res[1];
		$parameterNames = $res[2];
		$matches = $res[3];

		if($url != $pre_route && !($params)){
			return;
		}
		else if($_SERVER['REQUEST_METHOD'] != "POST"){
			http_response_code(403);
			return;
		}
		$this->ntf = false;
		$post = $_POST;
		if(empty($_POST)){
			$data = file_get_contents("php://input");
			$post = json_decode($data, true);
		}
		if(array_key_exists("middleware", $functions)){
			$middle = new Middle();
			$middle->exec_middleware($pre_route, $functions);
		}
		$parameters = [];
		if ($params) {
			$parameters = array_intersect_key($matches, array_flip($parameterNames[1]));
		}
		$closure = $functions[0];
		$parameters = array_merge($parameters, ["POST"=>$post]);
		call_user_func_array($closure, $parameters);
		exit;
	}

	private function process_url($url){
		$route = $_SERVER['REQUEST_URI'];
		$route = strstr($route, '?') ? strstr($route, '?', true): $route;  
		$url = $this->use.$url;

		$pre_route = str_replace($this->use, '', $route);

		// obteniendo parametros
		$urlRule = preg_replace('/:([^\/]+)/', '(?<\1>[^/]+)', $url);
		$urlRule = str_replace('/', '\/', $urlRule);
	
		preg_match_all('/:([^\/]+)/', $url, $parameterNames);
		$params = preg_match('/^' . $urlRule . '\/*$/s', $route, $matches);
		return [$pre_route, $params, $parameterNames, $matches];
	}

	public function error_404($dir){
		if($this->ntf == false){
			return;
		}
		$closure = function ()
		{
			include_once("./templates/ntf.html");
		};
		$parameters = [];
		return call_user_func_array($closure, $parameters);
	}
}
?>