<?php

namespace App {

    class Request
    {
        /**
         * @var array $headers
         */
        private $headers;

        /**
         * @var string $path
         */
        private $path;

        /**
         * Request constructor.
         * @param string $path
         * @param array $headers
         */
        public function __construct(
            string $path,
            array $headers
        )
        {
            $this->headers = $headers;
            $this->path = $path;
        }

        /**
         * @return string
         */
        public function getPath(): string
        {
            return $this->path;
        }

        /**
         * @return bool
         */
        public function isAjax(): bool
        {
            $this->findHeader('x-requested-with', $found);

            return strtolower($found) === strtolower('XMLHttpRequest');
        }

        /**
         * @param int $status
         * @param string $data
         */
        public function emit(
            int $status,
            string $data
        )
        {
            static $headerSent;

            $data = implode('', [
                'data: ',
                $data,
                PHP_EOL,
                PHP_EOL
            ]);

            if (!$headerSent) {
                $headerSent = true;
				ob_implicit_flush();

                $this->respond($status, $data, [
                    'Content-Type' => 'text/event-stream'
                ]);
            } else {
                $this->respond($status, $data);
            }

            @ob_end_flush();
            flush();
        }

        /**
         * @param int $status
         * @param string $data
         * @param array $headers
         */
        public function respond(
            int $status,
            string $data,
            array $headers = []
        )
        {
            http_response_code($status);

            foreach ($headers as $name => $header) {
                header("$name: $header" . PHP_EOL . PHP_EOL);
            }

            $output = fopen('php://output', 'w');
            fwrite($output, $data);
            fclose($output);
        }

        /**
         * @param string $name
         * @param string|null &$found
         * @return $this
         */
        private function findHeader(
            string $name,
            string &$found = null
        ): self
        {
            $headers = getallheaders();
            $names = array_keys($headers);
            $key = array_search(strtolower($name), array_map('strtolower', $names));

            if ($key !== false) {
                $found = $headers[$names[$key]];
            }

            return $this;
        }
    }
}