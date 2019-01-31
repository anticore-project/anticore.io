<?php

namespace App {

    use PHPDOM\HTML\Document;
    use PHPDOM\HTML\DocumentFragment;

    class View
    {
        /**
         * @var string $branding
         */
        private $branding;

        /**
         * @var Document $document
         */
        private $document;

        /**
         * View constructor.
         * @param string $branding
         * @param string $filename
         */
        public function __construct(
            string $branding,
            string $filename
        )
        {
            $this->branding = $branding;
            $this->document = new Document(true);
            $this->document->loadSource(file_get_contents($filename));
        }

        /**
         * @param string $selector
         * @param string $filename
         * @return View
         */
        public function append(
            string $selector,
            string $filename
        ): View
        {
            $this->document->select($selector)
                ->append($this->loadFragment($filename));

            return $this;
        }

        /**
         * @param string $selector
         * @param string $filename
         * @return View
         */
        public function before(
            string $selector,
            string $filename
        ): View
        {
            $target = $this->document->select($selector);
            $target->parentNode->insert($this->loadFragment($filename), $target);

            return $this;
        }

        /**
         * @param string $filename
         * @return DocumentFragment
         */
        private function loadFragment(
            string $filename
        ): DocumentFragment
        {
            return $this->document->loadFragment(file_get_contents($filename));
        }

        /**
         * @param string $url
         * @param string|null $selector
         * @return string
         */
        public function stringify(
            string $url,
            string $selector = null
        ): string
        {
            $document = $this->document;

            if (!is_null($selector)) {
                return (string) $document->selectAll($selector);
            }

            $anchor = $document->select("body > nav a[href='$url']");
            $title = trim($document->select('main > h1')->textContent ?? '');
            $document->select('title')->append(sprintf($this->branding, $title));

            if ($anchor) {
                $anchor->classList->add('current');
            }

            return (string) $document;
        }
    }
}