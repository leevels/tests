<?php

declare(strict_types=1);

/*
 * This file is part of the ************************ package.
 * _____________                           _______________
 *  ______/     \__  _____  ____  ______  / /_  _________
 *   ____/ __   / / / / _ \/ __`\/ / __ \/ __ \/ __ \___
 *    __/ / /  / /_/ /  __/ /  \  / /_/ / / / / /_/ /__
 *      \_\ \_/\____/\___/_/   / / .___/_/ /_/ .___/
 *         \_\                /_/_/         /_/
 *
 * The PHP Framework For Code Poem As Free As Wind. <Query Yet Simple>
 * (c) 2010-2019 http://queryphp.com All rights reserved.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests\Debug\Middleware;

use Leevel\Debug\Debug;
use Leevel\Debug\Middleware\Debug as MiddlewareDebug;
use Leevel\Event\IDispatch;
use Leevel\Http\IRequest;
use Leevel\Http\IResponse;
use Leevel\Http\JsonResponse;
use Leevel\Leevel\Project as Projects;
use Leevel\Log\File as LogFile;
use Leevel\Log\ILog;
use Leevel\Log\Log;
use Leevel\Option\IOption;
use Leevel\Option\Option;
use Leevel\Session\File as SessionFile;
use Leevel\Session\ISession;
use Leevel\Session\Session;
use Tests\TestCase;

/**
 * debug test.
 *
 * @author Xiangmin Liu <635750556@qq.com>
 *
 * @since 2018.09.25
 *
 * @version 1.0
 */
class DebugTest extends TestCase
{
    public function testBaseUse()
    {
        $debug = $this->createDebug();
        $project = $debug->getProject();

        $middleware = new MiddlewareDebug($project, $debug);

        $request = $this->createRequest('http://127.0.0.1');

        $this->assertFalse($debug->isBootstrap());

        $this->assertNull($middleware->handle(function ($request) {
            $this->assertInstanceof(IRequest::class, $request);
            $this->assertSame('http://127.0.0.1', $request->getUri());
        }, $request));

        $this->assertTrue($debug->isBootstrap());
    }

    public function testTerminate()
    {
        $debug = $this->createDebug();
        $project = $debug->getProject();

        $middleware = new MiddlewareDebug($project, $debug);

        $request = $this->createRequest('http://127.0.0.1');
        $response = new JsonResponse(['foo' => 'bar']);

        $this->assertFalse($debug->isBootstrap());

        $this->assertNull($middleware->handle(function ($request) {
            $this->assertInstanceof(IRequest::class, $request);
            $this->assertSame('http://127.0.0.1', $request->getUri());
        }, $request));

        $this->assertTrue($debug->isBootstrap());

        $this->assertSame('{"foo":"bar"}', $response->getContent());

        $this->assertNull($middleware->terminate(function ($request, $response) {
            $this->assertInstanceof(IRequest::class, $request);
            $this->assertSame('http://127.0.0.1', $request->getUri());
            $this->assertInstanceof(IResponse::class, $response);

            $content = $response->getContent();

            $this->assertContains('{"foo":"bar",":trace":', $content);
            $this->assertContains('"php":{"version":', $content);
            $this->assertContains('Starts from this moment with QueryPHP.', $content);
        }, $request, $response));
    }

    public function testHandleWithDebugIsFalse()
    {
        $debug = $this->createDebug(false);
        $project = $debug->getProject();

        $middleware = new MiddlewareDebug($project, $debug);

        $request = $this->createRequest('http://127.0.0.1');

        $this->assertFalse($debug->isBootstrap());

        $this->assertNull($middleware->handle(function ($request) {
            $this->assertInstanceof(IRequest::class, $request);
            $this->assertSame('http://127.0.0.1', $request->getUri());
        }, $request));

        $this->assertFalse($debug->isBootstrap());
    }

    public function testTerminateWithDebugIsFalse()
    {
        $debug = $this->createDebug(false);
        $project = $debug->getProject();

        $middleware = new MiddlewareDebug($project, $debug);

        $request = $this->createRequest('http://127.0.0.1');
        $response = new JsonResponse(['foo' => 'bar']);

        $this->assertFalse($debug->isBootstrap());

        $this->assertNull($middleware->handle(function ($request) {
            $this->assertInstanceof(IRequest::class, $request);
            $this->assertSame('http://127.0.0.1', $request->getUri());
        }, $request));

        $this->assertFalse($debug->isBootstrap());

        $this->assertSame('{"foo":"bar"}', $response->getContent());

        $this->assertNull($middleware->terminate(function ($request, $response) {
            $this->assertInstanceof(IRequest::class, $request);
            $this->assertSame('http://127.0.0.1', $request->getUri());
            $this->assertInstanceof(IResponse::class, $response);

            $content = $response->getContent();

            $this->assertNotContains('{"foo":"bar",":trace":', $content);
            $this->assertNotContains('"php":{"version":', $content);
            $this->assertNotContains('Starts from this moment with QueryPHP.', $content);
            $this->assertSame('{"foo":"bar"}', $content);
        }, $request, $response));
    }

    protected function createRequest(string $url): IRequest
    {
        $request = $this->createMock(IRequest::class);

        $request->cookies = new CookieTest();

        $request->method('getUri')->willReturn($url);
        $this->assertEquals($url, $request->getUri());

        return $request;
    }

    protected function createDebug(bool $debug = true): Debug
    {
        return new Debug($this->createProject($debug));
    }

    protected function createProject(bool $debug = true): Project
    {
        $project = new Project();

        $project->instance('session', $this->createSession());

        $project->instance('log', $this->createLog());

        $project->instance('option', $this->createOption($debug));

        $eventDispatch = $this->createMock(IDispatch::class);

        $eventDispatch->method('handle')->willReturn(null);
        $this->assertNull($eventDispatch->handle('event'));

        $project->singleton(IDispatch::class, $eventDispatch);

        return $project;
    }

    protected function createSession(): ISession
    {
        $file = new SessionFile([
            'path' => __DIR__.'/cacheFile',
        ]);

        $session = new Session($file);

        $this->assertInstanceof(ISession::class, $session);

        return $session;
    }

    protected function createLog(): ILog
    {
        $file = new LogFile([
            'path' => __DIR__.'/cacheLog',
        ]);

        $log = new Log($file);

        $this->assertInstanceof(ILog::class, $log);

        return $log;
    }

    protected function createOption(bool $debug = true): IOption
    {
        $data = [
            'app' => [
                'environment'       => 'environment',
                'debug'             => $debug,
            ],
            'debug' => [
                'json'       => true,
                'console'    => true,
                'javascript' => true,
            ],
        ];

        $option = new Option($data);

        $this->assertInstanceof(IOption::class, $option);

        return $option;
    }
}

class Project extends Projects
{
    protected function registerBaseProvider(): void
    {
    }
}

class CookieTest
{
    public function get(string $name, $default = null)
    {
    }
}
