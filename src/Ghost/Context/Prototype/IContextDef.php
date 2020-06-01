<?php

/**
 * This file is part of CommuneChatbot.
 *
 * @link     https://github.com/thirdgerb/chatbot
 * @document https://github.com/thirdgerb/chatbot/blob/master/README.md
 * @contact  <thirdgerb@gmail.com>
 * @license  https://github.com/thirdgerb/chatbot/blob/master/LICENSE
 */

namespace Commune\Ghost\Context\Prototype;

use Commune\Blueprint\Ghost\Cloner;
use Commune\Blueprint\Ghost\Context;
use Commune\Support\Parameter\ParamDefs;
use Commune\Blueprint\Ghost\Dialog;
use Commune\Blueprint\Ghost\MindDef\AliasesForContext;
use Commune\Blueprint\Ghost\MindDef\MemoryDef;
use Commune\Blueprint\Ghost\MindDef\StageDef;
use Commune\Blueprint\Ghost\MindMeta\IntentMeta;
use Commune\Blueprint\Ghost\Ucl;
use Commune\Blueprint\Ghost\MindMeta\MemoryMeta;
use Commune\Blueprint\Ghost\MindMeta\StageMeta;
use Commune\Support\Parameter\IParamDefs;
use Commune\Ghost\IMindDef\IMemoryDef;
use Commune\Ghost\Stage\InitStage;
use Commune\Ghost\Support\ContextUtils;
use Commune\Support\Option\AbsOption;
use Commune\Support\Option\Meta;
use Commune\Support\Option\Wrapper;
use Commune\Blueprint\Ghost\MindMeta\ContextMeta;
use Commune\Blueprint\Ghost\MindDef\ContextDef;
use Commune\Blueprint\Exceptions\Logic\InvalidArgumentException;

/**
 * @author thirdgerb <thirdgerb@gmail.com>
 *
 * @property-read string $name      当前配置的 ID
 * @property-read string $title     标题
 * @property-read string $desc      简介
 *
 *
 * ## context wrapper
 * @property-read string $contextWrapper       Context 的包装器.
 *
 *
 * ## 基础属性
 * @property-read int $priority                     语境的默认优先级
 * @property-read IntentMeta $asIntent
 *
 * @property-read string[] $queryNames              context 请求参数键名的定义, 如果是列表则要加上 []
 *
 * @property-read string[] $memoryScopes
 * @property-read array $memoryParams
 *
 * @property-read null|array $comprehendPipes
 *
 * @property-read null|string $onCancel
 * @property-read null|string $onQuit
 *
 * @property-read string[] $stageRoutes
 * @property-read string[] $contextRoutes
 *
 *
 * @property-read StageMeta[] $stages
 *
 */
class IContextDef extends AbsOption implements ContextDef
{

    /**
     * @var MemoryDef
     */
    protected $_asMemoryDef;

    /**
     * @var StageDef
     */
    protected $_asStageDef;

    /**
     * @var ParamDefs
     */
    protected $_queryCollection;

    /**
     * @var ParamDefs
     */
    protected $_paramCollection;

    /**
     * @var ParamDefs
     */
    protected $_entityParams;

    public static function stub(): array
    {
        return [
            'name' => '',
            'title' => '',
            'desc' => '',

            'contextWrapper' => '',

            'priority' => 0,
            'asIntent' => IntentMeta::stub(),

            'queryNames' => [],

            'memoryScopes' => null,
            'memoryParams' => [],

            'comprehendPipes' => null,

            'onCancel' => null,
            'onQuit' => null,
            'stageRoutes' => [],
            'contextRoutes' => [],

            'stages' => [],
        ];
    }

    public static function relations(): array
    {
        return [
            'stages[]' => StageMeta::class,
            'asIntent' => IntentMeta::class,
        ];
    }

    public function _filter(array $data): void
    {
        $asIntent = $data['asIntent'] ?? [];

        if (is_array($asIntent)) {
            $asIntent = IntentMeta::mergeStageInfo(
                $asIntent,
                $data['name'] ?? '',
                $data['title'] ?? '',
                $data['desc'] ?? ''
            );
            $data['asIntent'] = $asIntent;
        }

        $stages = $data['stages'] ?? [];
        foreach ($stages as $shortName => $stage) {
            $stages[$shortName] = StageMeta::mergeContextInfo(
                $stage,
                $data['name'] ?? '',
                $stage['stageName'] ?? strval($shortName)
            );
        }

        $data['stages'] = $stages;

        parent::_filter($data);
    }

    public static function validate(array $data): ? string /* errorMsg */
    {
        $name = $data['name'] ?? '';

        if (!ContextUtils::isValidContextName($name)) {
            return "contextName $name is invalid";
        }

        return parent::validate($data);
    }

    public function __get_contextWrapper() : string
    {
        $wrapper = $this->_data['contextWrapper'] ?? '';
        $wrapper = empty($wrapper)
            ? IContext::class
            : $wrapper;

        return AliasesForContext::getOriginFromAlias($wrapper);
    }

    public function __set_contextWrapper($name, $value) : void
    {
        $this->_data[$name] = AliasesForContext::getAliasOfOrigin($value);
    }

    /*------ wrap -------*/

    /**
     * @return ContextMeta
     */
    public function getMeta(): Meta
    {
        $config = $this->toArray();
        unset($config['name']);
        unset($config['title']);
        unset($config['desc']);

        return new ContextMeta([
            'name' => $this->name,
            'title' => $this->title,
            'desc' => $this->desc,
            'wrapper' => static::class,
            'config' => $config
        ]);
    }

    /**
     * @param Meta $meta
     * @return Wrapper
     */
    public static function wrap(Meta $meta): Wrapper
    {
        if (!$meta instanceof ContextMeta) {
            throw new InvalidArgumentException(
                __METHOD__
                . ' only accept meta of subclass ' . ContextMeta::class
            );
        }

        $config = $meta->config;
        $config['name'] = $meta->name;
        $config['title'] = $meta->title;
        $config['desc'] = $meta->desc;
        return new static($config);
    }

    /*------ properties -------*/

    public function getName(): string
    {
        return $this->name;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getDescription(): string
    {
        return $this->desc;
    }

    public function getPriority(): int
    {
        return $this->priority;
    }

    public function onCancelStage(): ? string
    {
        return $this->onCancel;
    }

    public function onQuitStage(): ? string
    {
        return $this->onQuit;
    }

    public function commonStageRoutes(): array
    {
        return $this->stageRoutes;
    }

    public function commonContextRoutes(): array
    {
        return $this->contextRoutes;
    }

    public function getScopes(): array
    {
        return $this->memoryScopes;
    }

    public function getDependingNames(): array
    {
        return $this->dependingNames;
    }

    public function comprehendPipes(Dialog $current): ? array
    {
        return $this->comprehendPipes;
    }

    /*------ parameters -------*/

    public function getQueryDefs(): ParamDefs
    {
        return $this->_queryCollection
            ?? $this->_queryCollection = IParamDefs::create($this->queryParams);
    }

    public function getParamsDefs(): ParamDefs
    {
        return $this->_paramCollection
            ?? $this->_paramCollection = IParamDefs::create($this->memoryParams);
    }


    /*------ relations -------*/

    public function asMemoryDef() : MemoryDef
    {
        return $this->_asMemoryDef
            ?? $this->_asMemoryDef = new IMemoryDef(new MemoryMeta([
                'name' => $this->name,
                'title' => $this->title,
                'desc' => $this->desc,
                'scopes' => $this->memoryScopes,
                'params' => $this->memoryParams,
            ]));
    }

    public function asStageDef() : StageDef
    {
        return $this->_asStageDef
            ?? $this->_asStageDef = new InitStage([
                'name' => $this->name,
                'contextName' => $this->name,
                'title' => $this->title,
                'desc' => $this->desc,
                'stageName' => '',
                'asIntent' => $this->asIntent,
            ]);
    }


    /*------ to context -------*/

    public function wrapContext(Cloner $cloner, Ucl $ucl): Context
    {
        return call_user_func(
            [$this->contextWrapper, Context::WRAP_FUNC],
            $cloner,
            $ucl
        );
    }

    /*------ stages -------*/

    public function eachPredefinedStage(): \Generator
    {
        foreach ($this->stages as $stageMeta) {
            yield $stageMeta->getWrapper();
        }
    }

    public function getPredefinedStageNames(bool $isFullname = false): array
    {
        return array_map(
            function(StageMeta $meta) use ($isFullname) {
                if ($isFullname) {
                    return $meta->name;
                }

                return $meta->stageName;
            },
            $this->stages
        );
    }

    public function firstStage(): ? string
    {
        foreach ($this->stages as $stage) {
            return ContextUtils::parseShortStageName(
                $stage->name,
                $this->name
            );
        }

        return null;
    }

    public function __destruct()
    {
        $this->_paramCollection = null;
        $this->_queryCollection = null;
        $this->_entityParams = null;
        $this->_asMemoryDef = null;
        $this->_asStageDef = null;

        parent::__destruct();
    }

}