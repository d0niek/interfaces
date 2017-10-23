<?php
declare(strict_types=1);

namespace eXtalion\Component\Interfaces\Traits;

use eXtalion\Component\Routing\Route;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * @author Damian Glinkowski <damianglinkowski@gmail.com>
 */
trait FrontController
{
    /**
     * @inheritDoc
     * @see \eXtalion\Component\Interfaces\FrontController
     */
    public function dispatch(
        Route $route,
        ServerRequestInterface $request
    ): ResponseInterface {
        $controller = $route->controller();
        $controller->request = $request;

        $parameters = $route->extractParameters(
            $request->getUri()->getPath()
        );

        if (is_callable($controller)) {
            return $controller(...array_values($parameters));
        } elseif (method_exists($controller, 'execute')) {
            return $controller->execute(...array_values($parameters));
        } elseif (method_exists($controller, 'action')) {
            return $controller->action(...array_values($parameters));
        }

        throw new \Error(
            'Do not known how to run ' . get_class($controller)
        );
    }
}
