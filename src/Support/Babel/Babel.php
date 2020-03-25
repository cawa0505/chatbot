<?php

/**
 * This file is part of CommuneChatbot.
 *
 * @link     https://github.com/thirdgerb/chatbot
 * @document https://github.com/thirdgerb/chatbot/blob/master/README.md
 * @contact  <thirdgerb@gmail.com>
 * @license  https://github.com/thirdgerb/chatbot/blob/master/LICENSE
 */

namespace Commune\Support\Babel;


/**
 * 将字符串反序列化为对象的机制. 用于各端之间传输信息.
 * 至于用到 json 还是 proto 还是什么, 是否加密, 这里不管.
 *
 * @author thirdgerb <thirdgerb@gmail.com>
 */
interface Babel
{
    /**
     * 注册一个反序列化机制.
     *
     * @param string $babelId
     * @param string $serializable
     */
    public function register(string $babelId, string $serializable) : void;

    /**
     * 序列化, 通常有一个加密环节.
     * @param BabelSerializable $serializable
     * @return string
     */
    public function serialize(BabelSerializable $serializable) : string;

    /**
     * 反序列化. 通常还有一个解密环节.
     * @param string $babelId
     * @param string $input
     * @return null|mixed 如果为 null, 表示无法反序列化.
     */
    public function unSerialize(string $babelId, string $input);

}