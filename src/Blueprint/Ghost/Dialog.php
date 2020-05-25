<?php

/**
 * This file is part of CommuneChatbot.
 *
 * @link     https://github.com/thirdgerb/chatbot
 * @document https://github.com/thirdgerb/chatbot/blob/master/README.md
 * @contact  <thirdgerb@gmail.com>
 * @license  https://github.com/thirdgerb/chatbot/blob/master/LICENSE
 */

namespace Commune\Blueprint\Ghost;

use Commune\Blueprint\Ghost\Operate;
use Commune\Blueprint\Ghost\Operate\Operator;
use Commune\Blueprint\Ghost\Runtime\Process;
use Commune\Blueprint\Ghost\Runtime\Task;
use Commune\Blueprint\Ghost\Tools\Caller;
use Commune\Blueprint\Ghost\Tools\Deliver;
use Commune\Blueprint\Ghost\Memory\Recollection;
use Commune\Blueprint\Ghost\Tools\Hearing;

/**
 * @author thirdgerb <thirdgerb@gmail.com>
 *
 *
 * @property-read Cloner $cloner        当前对话机器人的分身.
 * @property-read Context $context      当前的上下文语境.
 * @property-read Ucl $ucl              当前语境的地址.
 * @property-read Task $task            当前语境的任务状态.
 * @property-read Process $process      当前多轮对话运行中的进程.
 * @property-read Dialog|null $prev     上一个 Dialog 对象.
 */
interface Dialog
{
    /*----- dialog event -----*/

    // 任意 dialog
    const ANY           = Dialog::class;

    // 进入一个Stage 时触发的事件.
    const ACTIVATE      = Dialog\Activate::class;
    // 由用户消息触发的事件.
    const RECEIVE       = Dialog\Receive::class;
    // stage 回调时
    const RESUME        = Dialog\Resume::class;

    /* activate 激活一个 stage */

    // 相同的 context 里一个 stage 进入另一个 stage
    const STAGING       = Dialog\Activate\Staging::class;
    // 一个 context 依赖到另一个 context 时
    const DEPENDED      = Dialog\Activate\Depend::class;
    // 返回到根路径.
    const RESET         = Dialog\Activate\Reset::class;
    // 重新激活当前语境.
    const REACTIVATE    = Dialog\Activate\Reactivate::class;
    // 其它语境重定向到当前语境.
    const REDIRECT      = Dialog\Activate\Redirect::class;


    /* resume 将一个挂起的 stage 恢复. */

    // sleep -> wake
    const WAKE          = Dialog\Resume\Wake::class;
    // depending -> callback
    const CALLBACK      = Dialog\Resume\Callback::class;
    // dying -> restore
    const RESTORE       = Dialog\Resume\Restore::class;
    // 一个 blocking context 重新占据对话
    const PREEMPT       = Dialog\Resume\Preempt::class;


    /**
     * Dialog 自身所代表的事件类型
     *
     * @param string $statusType
     * @return bool
     */
    public function isEvent(string $statusType) : bool;

    /*----- call -----*/

    /**
     * 上下文相关的 IoC 容器.
     * @return Caller
     */
    public function caller() : Caller;


    /*----- conversation -----*/

    /**
     * 发送消息给用户
     * @return Deliver
     */
    public function send() : Deliver;


    /*----- memory -----*/

    /**
     * 获取一个记忆体.
     * @param string $name
     * @return Recollection
     */
    public function recall(string $name) : Recollection;

    /*----- await -----*/

    /**
     * 等待用户回复
     *
     * @param array $stageRoutes
     * @param array $contextRoutes
     * @param int|null $expire
     * @return Operate\Await
     */
    public function await(
        array $stageRoutes = [],
        array $contextRoutes = [],
        int $expire = null
    ) : Operate\Await;


    /**
     * @param bool $silent
     * @return Operator
     */
    public function rewind(bool $silent = false) : Operator;

    /**
     * @return Operator
     */
    public function dumb() : Operator;

    /**
     * @param int $step
     * @return Operator
     */
    public function backStep(int $step = 1) : Operator;


    /**
     * 主动强调无法理解当前对话.
     * 不会继续尝试 Wake 其它对话.
     *
     * @param bool $silent
     * @return Operator
     */
    public function confuse(bool $silent = false) : Operator;

    /*------ staging ------*/

    /**
     * 添加 stage 管道到当前管道的开头. 然后进入第一个管道.
     * 如果没有管道存在, 则会触发 fulfill 流程.
     *
     * @param string $stageName
     * @param string ...$stageNames
     * @return Operator
     */
    public function goStage(string $stageName, string ...$stageNames) : Operator;

    /**
     * @param string|null $ifNone
     * @return Operator
     */
    public function next(string $ifNone = null) : Operator;

    /**
     * @return Operator
     */
    public function reactivate() : Operator;

    /*------ heed ------*/

    /**
     * 对输入的消息进行链式匹配操作.
     * @return Hearing
     */
    public function hearing() : Hearing;

    /*------ redirect ------*/

    /**
     * 重定向到另一个 Context.
     *
     * @param Ucl $ucl
     * @return Operator
     */
    public function redirectTo(Ucl $ucl) : Operator;

    /**
     * @param Ucl|null $root
     * @return Operator
     */
    public function reset(Ucl $root = null) : Operator;

    /*------ suspend ------*/

    /**
     * 依赖一个目标 Context. 当目标 Context fulfill 时,
     * 会调用当前 Stage 的 onFulfill 方法.
     *
     * @param Ucl $dependUcl
     * @param string|null $fieldName
     * @return Operator
     */
    public function dependOn(Ucl $dependUcl, string $fieldName = null) : Operator;

    /**
     * 将自己压入 block 状态, 然后进入 $to 语境.
     *
     * @param Ucl $target
     * @param int|null $priority
     * @return Operator
     */
    public function blockTo(Ucl $target, int $priority = null) : Operator;

    /**
     * 让当前 Context 进入 sleep 状态
     *
     * @param string[] $wakenStages  指定这些 Stage, 可以在匹配意图后主动唤醒.
     * @param Ucl|string|null $target
     * @return Operator
     */
    public function sleepTo(Ucl $target, array $wakenStages = []) : Operator;


    /*------ exiting ------*/

    /**
     * 完成当前语境.
     *
     * @param array $restoreStage
     * @param int $gcTurns
     * @return mixed
     */
    public function fulfill(
        array $restoreStage = [],
        int $gcTurns = 0
    ) : Operator;

    /**
     * 退出当前语境.
     * @return Operator
     */
    public function cancel() : Operator;

    /**
     * 退出整个对话
     * @return Operator
     */
    public function quit() : Operator;

}