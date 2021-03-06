<?php

/**
 * This file is part of CommuneChatbot.
 *
 * @link     https://github.com/thirdgerb/chatbot
 * @document https://github.com/thirdgerb/chatbot/blob/master/README.md
 * @contact  <thirdgerb@gmail.com>
 * @license  https://github.com/thirdgerb/chatbot/blob/master/LICENSE
 */

namespace Commune\Message\Host\QA;

use Commune\Protocals\HostMsg;
use Commune\Support\Message\AbsMessage;
use Commune\Protocals\HostMsg\Convo\QA\AnswerMsg;

/**
 * @author thirdgerb <thirdgerb@gmail.com>
 *
 * @property-read string $answer
 * @property-read string|null $choice
 */
class IAnswerMsg extends AbsMessage implements AnswerMsg
{
    public static function stub(): array
    {
        return [
            'answer' => '',
            'choice' => null,
        ];
    }

    public static function relations(): array
    {
        return [];
    }

    public function getAnswer(): string
    {
        return $this->answer;
    }

    public function getChoice(): ? string
    {
        $choice = $this->choice;
        return isset($choice)
            ? (string) $choice
            : null;
    }

    public function getProtocalId(): string
    {
        return $this->answer;
    }

    public function getLevel(): string
    {
        return HostMsg::INFO;
    }

    public function isEmpty(): bool
    {
        return empty($this->_data['answer'])
            && empty($this->_data['choice']);
    }

    public function getText(): string
    {
        return $this->answer;
    }


}