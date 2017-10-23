<?php
declare(strict_types=1);

namespace eXtalion\ComponentTest\Interfaces\FrontController;

use eXtalion\Component\Interfaces\FrontController;
use eXtalion\Component\Interfaces\Traits;
use eXtalion\Component\Routing\Route;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UriInterface;

/**
 * @author Damian Glinkowski <damianglinkowski@gmail.com>
 */
final class TraitTest extends TestCase
{
    /**
     * @var \eXtalion\Component\Interfaces\FrontController
     */
    protected static $frontController;

    public static function setUpBeforeClass(): void
    {
        self::$frontController = new class implements FrontController {
            use Traits\FrontController;
        };
    }

    public static function tearDownAfterClass(): void
    {
        self::$frontController = null;
    }

    protected function setUp()
    {
        $uri = $this->createMock(UriInterface::class);
        $uri->method('getPath')
            ->willReturn('/user/3/block');

        $this->request = $this->createMock(ServerRequestInterface::class);
        $this->request->method('getUri')
            ->willReturn($uri);

        $this->response = $this->createMock(ResponseInterface::class);
    }

    protected function tearDown()
    {
        $this->request = null;
        $this->response = null;
    }

    public function testControllerClassIsCallable(): void
    {
        $route = $this->getMockForAbstractClass(
            Route::class,
            [
                '/user/{id}/{action}',
                get_class(new class {
                    public function __construct(
                        ResponseInterface $response = null
                    ) {
                        $this->response = $response;
                    }
                    public function __invoke(): ResponseInterface
                    {
                        return $this->response;
                    }
                }),
                [$this->response]
            ]
        );

        $this->assertEquals(
            $this->response,
            self::$frontController->dispatch($route, $this->request)
        );
    }

    public function testControllerClassIsCallableWithArguments(): void
    {
        $controller = new class {
            public static $arguments = null;
            public function __construct(ResponseInterface $response = null)
            {
                $this->response = $response;
            }
            public function __invoke(
                string $id,
                string $action
            ): ResponseInterface {
                self::$arguments = $id . '_' . $action;
                return $this->response;
            }
        };
        $route = $this->getMockForAbstractClass(
            Route::class,
            [
                '/user/{id}/{action}',
                get_class($controller),
                [$this->response]
            ]
        );

        self::$frontController->dispatch($route, $this->request);
        $this->assertEquals('3_block', $controller::$arguments);
    }

    public function testControllerClassHasExecuteMethod(): void
    {
        $route = $this->getMockForAbstractClass(
            Route::class,
            [
                '/user/{id}/{action}',
                get_class(new class {
                    public function __construct(
                        ResponseInterface $response = null
                    ) {
                        $this->response = $response;
                    }
                    public function execute(): ResponseInterface
                    {
                        return $this->response;
                    }
                }),
                [$this->response]
            ]
        );

        $this->assertEquals(
            $this->response,
            self::$frontController->dispatch($route, $this->request)
        );
    }

    public function testControllerClassHasExecuteMethodWithArguments(): void
    {
        $controller = new class {
            public static $arguments = null;
            public function __construct(ResponseInterface $response = null)
            {
                $this->response = $response;
            }
            public function execute(
                string $id,
                string $action
            ): ResponseInterface {
                self::$arguments = $id . '_' . $action;
                return $this->response;
            }
        };
        $route = $this->getMockForAbstractClass(
            Route::class,
            [
                '/user/{id}/{action}',
                get_class($controller),
                [$this->response]
            ]
        );

        self::$frontController->dispatch($route, $this->request);
        $this->assertEquals('3_block', $controller::$arguments);
    }

    public function testControllerClassHasActionMethod(): void
    {
        $route = $this->getMockForAbstractClass(
            Route::class,
            [
                '/user/{id}/{action}',
                get_class(new class {
                    public function __construct(
                        ResponseInterface $response = null
                    ) {
                        $this->response = $response;
                    }
                    public function action(): ResponseInterface
                    {
                        return $this->response;
                    }
                }),
                [$this->response]
            ]
        );

        $this->assertEquals(
            $this->response,
            self::$frontController->dispatch($route, $this->request)
        );
    }

    public function testControllerClassHasActionMethodWithArguments(): void
    {
        $controller = new class {
            public static $arguments = null;
            public function __construct(ResponseInterface $response = null)
            {
                $this->response = $response;
            }
            public function action(
                string $id,
                string $action
            ): ResponseInterface {
                self::$arguments = $id . '_' . $action;
                return $this->response;
            }
        };
        $route = $this->getMockForAbstractClass(
            Route::class,
            [
                '/user/{id}/{action}',
                get_class($controller),
                [$this->response]
            ]
        );

        self::$frontController->dispatch($route, $this->request);
        $this->assertEquals('3_block', $controller::$arguments);
    }

    public function testCanNotRunControllerClass(): void
    {
        $route = $this->getMockForAbstractClass(
            Route::class,
            [
                '/user/{id}/{action}',
                get_class(new class {})
            ]
        );

        $this->expectException(\Error::class);

        self::$frontController->dispatch($route, $this->request);
    }
}
