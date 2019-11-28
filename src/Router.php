<?php

namespace App;

use AltoRouter;
use App\Security\ForbiddenException;

class Router 
{
    /**
     * @var string
     */
    private $viewPath;

    /**
     * @var AltoRouter
     */
    private $router;

    public function __construct(string $viewPath)
    {
        $this->viewPath = $viewPath;
        $this->router = new AltoRouter();
    }

    public function get(string $url, string $view, ?string $name = null): self
    {
        $this->router->map('GET',$url,$view,$name);
        return $this;
    }

    public function post(string $url, string $view, ?string $name = null): self
    {
        $this->router->map('POST',$url,$view,$name);
        return $this;
    }

    public function match(string $url, string $view, ?string $name = null): self
    {
        $this->router->map('GET|POST',$url,$view,$name);
        return $this;
    }

    public function run(): self
    {
        $match = $this->router->match();
        $view = $match['target'];
        $params = $match['params'];
        $isAdmin = strpos($view,'admin/') !== false;
        $layout = $isAdmin ? 'admin/layouts/default' : 'layouts/default';
        $router = $this;
        try {
            ob_start();
            $view !== null ? require $this->viewPath . DIRECTORY_SEPARATOR . $view . '.php' : require $this->viewPath . DIRECTORY_SEPARATOR . 'e404' . '.php';
            $content = ob_get_clean();
            require $this->viewPath . DIRECTORY_SEPARATOR . $layout . '.php';    
        } catch (ForbiddenException $e) {
            header('Location: ' . $router->url('login') . '?forbidden=1');
        }
        return $this;
    }

    public function url(string $name, array $params = []): ?string
    {
        return $this->router->generate($name,$params);
    }
}