<?php

namespace App {

    class Controller
    {
        /**
         * @var View $view
         */
        private $view;

        /**
         * Controller constructor.
         * @param View $view
         */
        public function __construct(
            View $view
        )
        {
            $this->view = $view;
        }

        /**
         * @param Request $request
         * @param string $route
         * @param int $status
         */
        public function get(
            Request $request,
            string $route,
            int $status
        ): void
        {
            if ($request->getPath() === '/events') {
                $this->events($request, $route, $status);

                return;
            }

            $request->respond(
                $status,
                $this->view
                    ->before('body > script', $route)
                    ->stringify(
                        $request->getPath(),
                        $request->isAjax() ? 'main' : null
                    )
            );
        }

        /**
         * @param Request $request
         * @param string $route
         * @param int $status
         */
        public function events(
            Request $request,
            string $route,
            int $status
        ): void
        {
            $this->view->before('body > script', $route);

            while (1) {
                $key = (int) mt_rand(1, 10);
                $request->emit($status, $this->view
                    ->stringify(
                        $request->getPath(),
                        "body > .events li:nth-of-type($key)"
                    ));

                sleep(1);
            }
        }
    }
}