<?php

/**
 * This file is part of CommuneChatbot.
 *
 * @link     https://github.com/thirdgerb/chatbot
 * @document https://github.com/thirdgerb/chatbot/blob/master/README.md
 * @contact  <thirdgerb@gmail.com>
 * @license  https://github.com/thirdgerb/chatbot/blob/master/LICENSE
 */

namespace Commune\Framework;

use Commune\Blueprint\Configs\Nest\ProtocalOption;
use Commune\Blueprint\Framework\App;
use Commune\Blueprint\Framework\ReqContainer;
use Commune\Blueprint\Framework\Session;
use Commune\Framework\Exceptions\SerializeForbiddenException;
use Commune\Support\Pipeline\OnionPipeline;
use Commune\Support\Protocal\ProtocalInstance;
use Commune\Support\RunningSpy\Spied;
use Commune\Support\RunningSpy\SpyTrait;
use Commune\Support\Uuid\HasIdGenerator;
use Commune\Support\Uuid\IdGeneratorHelper;


/**
 * @author thirdgerb <thirdgerb@gmail.com>
 */
abstract class ASession implements Session, Spied, HasIdGenerator
{
    use SpyTrait, IdGeneratorHelper;

    const SINGLETONS = [
    ];

    /**
     * @var ReqContainer
     */
    protected $container;

    /**
     * @var string
     */
    protected $sessionId;

    /*------ cached ------*/

    /**
     * @var string[]
     */
    protected $listened = [];

    /**
     * Session 级别的单例.
     * @var array
     */
    protected $singletons = [];

    /**
     * @var string
     */
    protected $traceId;

    /**
     * @var bool
     */
    protected $finished = false;

    /**
     * @var bool
     */
    protected $stateless = false;

    /**
     * @var bool
     */
    protected $debug;

    /**
     * @var App
     */
    protected $app;

    /**
     * @var ProtocalOption[][]
     */
    protected $protocalMap;

    /**
     * ASession constructor.
     * @param ReqContainer $container
     * @param string $sessionId
     */
    public function __construct(ReqContainer $container, string $sessionId)
    {
        $this->container = $container;
        $this->traceId = $container->getId();
        $this->sessionId = $sessionId;
        static::addRunningTrace($this->traceId, $this->traceId);
    }

    /*------ id ------*/

    public function getSessionId(): string
    {
        return $this->sessionId;
    }


    /*------ abstract ------*/

    abstract protected function flushInstances() : void;

    abstract protected function saveSession() : void;


    public function isDebugging(): bool
    {
        return $this->debug ?? $this->debug = $this->getApp()->isDebugging();
    }


    /*------ components ------*/

    public function getContainer(): ReqContainer
    {
        return $this->container;
    }

    public function getApp(): App
    {
        return $this->app
            ?? $this->app = $this->container->make(App::class);
    }

    /*------ logic ------*/


    public function buildPipeline(array $pipes, string $via, \Closure $destination): \Closure
    {
        $pipeline = new OnionPipeline($this->container);
        $pipeline->through(...$pipes);
        $pipeline->via($via);
        return $pipeline->buildPipeline($destination);
    }

    public function getProtocalHandler(string $group, ProtocalInstance $protocal): ? callable
    {
        if (!isset($this->protocalMap)) {
            $options = $this->getProtocalOptions();
            foreach ($options as $option) {
                $this->protocalMap[$option->group][$option->protocal] = $option;
            }
        }

        $options = $this->protocalMap[$group] ?? [];

        if (empty($options)) {
            return null;
        }

        foreach ($options as $name => $option) {
            if ($protocal->isProtocal($name)) {
                $abstract = $option->handler;
                $params = $option->params;
                $handlerIns = $this->getContainer()->make($abstract, $params);
                return $handlerIns;
            }
        }

        return null;
    }

    /**
     * @return ProtocalOption[]
     */
    abstract protected function getProtocalOptions() : array;


    /*------ status ------*/

    public function getTraceId(): string
    {
        return $this->traceId;
    }


    public function isFinished(): bool
    {
        return $this->finished;
    }

    /*------ event ------*/

    public function fire(Session\SessionEvent $event): void
    {
        $id = $event->getEventName();
        if (!isset($this->listened[$id])) {
            return;
        }

        // 执行所有的事件.
        foreach ($this->listened[$id] as $handler) {

            call_user_func($handler, $this, $event);
        }
    }

    public function listen(string $eventName, callable $handler): void
    {
        $this->listened[$eventName][] = $handler;
    }


    /*------ silence ------*/

    public function noState(): void
    {
        $this->stateless = true;
    }

    public function isStateless(): bool
    {
        return $this->stateless;
    }


    /*------ getter ------*/

    public function __get($name)
    {
        if ($name === 'container') {
            return $this->container;
        }

        $injectable = static::SINGLETONS[$name] ?? null;

        if (!empty($injectable)) {
            return $this->singletons[$name]
                ?? $this->singletons[$name] = $this->container->get($injectable);
        }

        return null;
    }

    /*------ finish ------*/

    public function finish(): void
    {
        if (!$this->isStateless()) {
            $this->saveSession();
        }

        $this->app = null;
        $this->listened = [];
        $this->singletons = [];
        $this->flushInstances();
        $this->finished = true;

        // container
        $this->container->destroy();
        $this->container = null;
    }


    public function __sleep()
    {
        throw new SerializeForbiddenException(static::class);
    }


    public function __destruct()
    {
        static::removeRunningTrace($this->traceId);
    }
}