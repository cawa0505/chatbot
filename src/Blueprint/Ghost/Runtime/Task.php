<?php

/**
 * This file is part of CommuneChatbot.
 *
 * @link     https://github.com/thirdgerb/chatbot
 * @document https://github.com/thirdgerb/chatbot/blob/master/README.md
 * @contact  <thirdgerb@gmail.com>
 * @license  https://github.com/thirdgerb/chatbot/blob/master/LICENSE
 */

namespace Commune\Blueprint\Ghost\Runtime;

use Commune\Blueprint\Ghost\Ucl;

/**
 * Task 是 Context 在进程中的运行状态缓存.
 *
 * @author thirdgerb <thirdgerb@gmail.com>
 *
 * @property-read string $id
 * @property-read string $stage
 * @property-read string[] $paths
 *
 */
interface Task
{
    /*------- status -------*/

    /**
     * @return string
     */
    public function getId() : string;

    /**
     * @return Ucl
     */
    public function getUcl() : Ucl;

    /**
     * @param int $statusCode
     * @return bool
     */
    public function isStatus(int $statusCode) : bool;

    /**
     * @return int
     */
    public function getStatus() : int;

    /**
     * @param string[] $stages
     */
    public function addPaths(array $stages) : void;

    public function setPaths(array $stages) : void;

    public function insertPaths(array $stages) : void;

    /**
     * @return Ucl|null
     */
    public function popPath() : ? Ucl;

    /**
     * @param string|null $stage
     */
    public function onCancel(string $stage = null) : void;

    public function watchCancel() : ? Ucl;

    /**
     * @param string|null $stage
     */
    public function onQuit(string $stage = null) : void;

    public function watchQuit() : ? Ucl;
}