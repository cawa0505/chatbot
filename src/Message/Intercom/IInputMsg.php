<?php

/**
 * This file is part of CommuneChatbot.
 *
 * @link     https://github.com/thirdgerb/chatbot
 * @document https://github.com/thirdgerb/chatbot/blob/master/README.md
 * @contact  <thirdgerb@gmail.com>
 * @license  https://github.com/thirdgerb/chatbot/blob/master/LICENSE
 */

namespace Commune\Message\Intercom;

use Commune\Protocals\HostMsg;
use Commune\Protocals\Intercom\InputMsg;
use Commune\Protocals\Intercom\OutputMsg;
use Commune\Support\Utils\TypeUtils;

/**
 * @author thirdgerb <thirdgerb@gmail.com>
 *
 * @property string $messageId     自动生成的消息有序 ID
 * @property string $batchId       批次ID, 为空则是 messageId
 * @property string $sessionId     会话Id, 为空则是 guestId
 * @property string $creatorId     创建消息的用户 ID
 * @property string $creatorName   创建消息的用户名
 * @property int $createdAt        创建时间.
 * @property int $deliverAt        发送时间. 默认为0.
 * @property HostMsg $message      输入消息
 * @property string $convoId       多轮会话的 ID. 允许为空. 除非客户端有指定的 conversation.
 */
class IInputMsg extends AIntercomMsg implements InputMsg
{

    public static function instance(
        HostMsg $message,
        string $sessionId,
        string $creatorId = '',
        string $creatorName = '',
        string $convoId = '',
        int $deliverAt = null
    ) : self
    {
        $deliverAt = $deliverAt ?? time();
        $data = [
            'message' => $message,
            'sessionId' => $sessionId,
            'creatorId' => $creatorId,
            'creatorName' => $creatorName,
            'convoId' => $convoId,
            'deliverAt' => $deliverAt
        ];

        return new static($data);
    }

    public function isInvalid(): ? string
    {
        return TypeUtils::requireFields(
            $this->_data,
            ['messageId', 'message', 'sessionId']
        );
    }

    public function getBatchId(): string
    {
        return $this->messageId;
    }

    public function setSessionId(string $sessionId): void
    {
        $this->sessionId = $sessionId;
    }

    public function output(
        HostMsg $message,
        string $creatorId = '',
        string $creatorName = '',
        int $deliverAt = null,
        string $sessionId = null
    ): OutputMsg
    {
        $deliverAt = $deliverAt ?? time();
        return IOutputMsg::instance(
            $message,
            $this->getSessionId(),
            $this->getMessageId(),
            $creatorId,
            $creatorName,
            $this->getConvoId(),
            $deliverAt
        );
    }


}