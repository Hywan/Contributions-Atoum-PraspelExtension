<?php

/**
 * Hoa
 *
 *
 * @license
 *
 * New BSD License
 *
 * Copyright © 2007-2014, Ivan Enderlin. All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are met:
 *     * Redistributions of source code must retain the above copyright
 *       notice, this list of conditions and the following disclaimer.
 *     * Redistributions in binary form must reproduce the above copyright
 *       notice, this list of conditions and the following disclaimer in the
 *       documentation and/or other materials provided with the distribution.
 *     * Neither the name of the Hoa nor the names of its contributors may be
 *       used to endorse or promote products derived from this software without
 *       specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
 * AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE
 * ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDERS AND CONTRIBUTORS BE
 * LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR
 * CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF
 * SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS
 * INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN
 * CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)
 * ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 */

namespace {

/**
 * @author     Ivan Enderlin <ivan.enderlin@hoa-project.net>
 * @copyright  Copyright © 2007-2014 Ivan Enderlin.
 */

require_once dirname(dirname(dirname(dirname(dirname(__DIR__))))) .
             DIRECTORY_SEPARATOR . 'autoload.php';

use Hoa\Core;
use Hoa\Router;
use Hoa\Dispatcher;
use Hoa\Console;

Core::enableErrorHandler();
Core::enableExceptionHandler();

try {

    $router = new Router\Cli();
    $router->get(
        'g',
        '((?<command>\w+)(?<_tail>.*?))?',
        'main',
        'main',
        array(
            'command' => 'welcome'
        )
    );

    $dispatcher = new Dispatcher\Basic(array(
        'synchronous.controller'
            => 'Atoum\PraspelExtension\Bin\Command\(:%variables.command:lU:)',
        'synchronous.action'
            => 'main'
    ));
    $dispatcher->setKitName('Hoa\Console\Dispatcher\Kit');
    exit($dispatcher->dispatch($router));
}
catch ( Core\Exception $e ) {

    $message = $e->raise(true);
    $code    = 1;
}
catch ( \Exception $e ) {

    $message = $e->getMessage();
    $code    = 2;
}

ob_start();

Console\Cursor::colorize('foreground(white) background(red)');
echo $message, "\n";
Console\Cursor::colorize('normal');
$content = ob_get_contents();

ob_end_clean();

file_put_contents('php://stderr', $content);
exit($code);

}
