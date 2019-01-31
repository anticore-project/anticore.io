<?php

namespace App {

    class Router
    {
        /**
         * @var string $default
         */
        private $default;

        /**
         * @var string[] $routes
         */
        private $routes;

        /**
         * Router constructor.
         * @param string $default
         * @param string[] $routes
         */
        private function __construct(
            string $default,
            array $routes
        )
        {
            $this->default = $default;
            $this->routes = $routes;
        }

        /**
         * @param string $default
         * @param string[] $routes
         * @return Router
         */
        public static function init(
            string $default,
            array $routes
        ): self
        {
            return new self($default, $routes);
        }

        /**
         * @param Request $request
         * @param Controller $controller
         */
        public function dispatch(
            Request $request,
            Controller $controller
        ): void
        {
            $path = $request->getPath();

            if (strpos($path, '/') !== 0) {
                $path = '/' . $path;
            }

            $route = $this->routes[$path] ?? $this->default;
            $status = $route === $this->default ? 404 : 200;
            $controller->get($request, $route, $status);
        }
    }
}