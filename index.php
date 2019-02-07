<?php
chdir(__DIR__);
error_reporting(0);
ini_set('display_errors', 0);
mb_internal_encoding('UTF-8');
require_once 'vendor/autoload.php';

use PHPDOM\HTML\SelectorCache;
use App\Controller;
use App\Request;
use App\Router;
use App\View;

SelectorCache::load('.cache/selectors.json');
Router::init('html/errors/404.html', [
    '/' => 'html/pages/home.html',
    '/account' => 'html/pages/account.html',
    '/musics' => 'html/pages/musics.html',
    '/files' => 'html/pages/files.html',
    '/books' => 'html/pages/books.html',
    '/events' => 'html/events.html'
])->dispatch(
    new Request($_SERVER['REQUEST_URI'], getallheaders()),
    new Controller(new View('%s | anticore.io', 'html/layout.html'))
);