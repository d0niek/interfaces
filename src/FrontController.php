<?php
declare(strict_types=1);

namespace eXtalion\Component\Interfaces;

use eXtalion\Component\Routing\Route;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * @author Damian Glinkowski <damianglinkowski@gmail.com>
 */
interface FrontController
{
    /**
     * Dispatch route controller
     *
     * @param \eXtalion\Component\Routing\Route $route
     * @param \Psr\Http\Message\ServerRequestInterface $request
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function dispatch(
        Route $route,
        ServerRequestInterface $request
    ): ResponseInterface;
}
