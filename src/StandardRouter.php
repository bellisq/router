<?php

namespace Bellisq\Router;

use Bellisq\MVC\ViewAbstract;
use Bellisq\Request\Request;
use Bellisq\Router\Capsules\RouteHandlerCapsule;
use Bellisq\Router\Containers\RoutesContainer;
use Bellisq\Router\RouterInterface;


/**
 * [Class] Standard Router
 *
 * @author Showsay You <akizuki.c10.l65@gmail.com>
 * @copyright 2018 Bellisq. All Rights Reserved.
 * @package bellisq/router
 * @since 1.0.0
 */
abstract class StandardRouter
    implements RouterInterface
{
    /**
     * Register routes.
     * The class RouteRegister is an immutable class.
     *
     * @param RouteRegister $routeRegister
     */
    abstract protected static function registerRoutes(RouteRegister $routeRegister): void;

    /** @var RoutesContainer */
    private $routesContainer;

    /**
     * StandardRouter constructor.
     */
    public function __construct()
    {
        static::registerRoutes($register = new RouteRegister($container = new RoutesContainer));
        $this->routesContainer = $container;
    }

    /**
     * @inheritdoc
     */
    public function route(Request $request): RouteResult
    {
        foreach ($this->routesContainer as $routeObject) {
            $ret = $routeObject->match($request);
            if (!is_null($ret)) {
                return $ret;
            }
        }
        return new RouteResult(
            new RouteHandlerCapsule(
                function (Request $request, RouteParameters $params): ViewAbstract {
                    return new class($request->line->protocol, $params->path) extends ViewAbstract
                    {
                        /** @var string */
                        private $protocol;

                        /** @var string */
                        private $path;

                        /**
                         * Constructor.
                         *
                         * @param string $protocol
                         * @param string $path
                         */
                        public function __construct(string $protocol, string $path)
                        {
                            $this->protocol = $protocol;
                            $this->path = $path;
                        }

                        /**
                         * Output standard 404.
                         */
                        public function dispatch(): void
                        {
                            header("{$this->protocol} 404 Not Found");
                            $path = htmlspecialchars($this->path, ENT_QUOTES | ENT_HTML5);
                            echo '<!DOCTYPE html>', PHP_EOL;
                            echo '<meta charset="UTF-8">';
                            echo '<title>404 Not Found</title>';
                            echo '<body>';
                            echo "<h1>{$this->protocol} 404 Not Found</h1>";
                            echo "<p>The requested URL {$path} was not found on this server.</p>";
                            echo '</body>';
                        }
                    };
                }
            ),
            new RouteParameters(['path' => $request->line->path])
        );
    }

    /**
     * @inheritdoc
     */
    public function getAccessor(): RoutesAccessor
    {
        return new RoutesAccessor($this->routesContainer);
    }
}