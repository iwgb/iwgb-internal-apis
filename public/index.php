<?php

$c = require '../bootstrap.php';

use Iwgb\Media\Handler;
use Pimple\Container;
use Siler\Container as Router;
use Siler\Http\Response;
use Siler\Route as http;

try {
    dispatch($c);
} catch (Exception $e) {
    _catch($e);
    throw $e;
//    Response\redirect('/error');
}

function dispatch(Container $c) {
    $path = "(?'path'[A-z0-9_=-]+)";

    http\get('', fn (array $args) => Response\redirect('/root/view'));
    http\get('/upload', new Handler\UploadForm($c));

    http\post('/upload', new Handler\Upload($c));
    http\post('/newFolder', new Handler\NewFolder($c));

    http\get("/{$path}/newFolder", new Handler\NewFolderForm($c));
    http\get("/{$path}/view", new Handler\View($c));
    http\get("/{$path}/delete", new Handler\Delete($c));

    http\get("/{$path}", fn (array $args) => Response\redirect('/root/view'));
}

function _catch(Exception $e): void {
    // do something
}

if (!Router\get(http\DID_MATCH, false)) {
//    Handler\RootHandler::notFound();
    Response\output('not found');
}
