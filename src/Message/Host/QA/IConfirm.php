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

use Commune\Blueprint\Ghost\Ucl;
use Commune\Protocals\Comprehension;
use Commune\Blueprint\Ghost\Cloner;
use Commune\Blueprint\Ghost\MindDef\EmotionDef;
use Commune\Protocals\HostMsg\Convo\QA\AnswerMsg;
use Commune\Protocals\HostMsg\Convo\QA\Confirm;
use Commune\Protocals\HostMsg\Convo\QA\Confirmation;
use Commune\Support\Struct\Struct;

/**
 * @author thirdgerb <thirdgerb@gmail.com>
 */
class IConfirm extends IQuestionMsg implements Confirm
{
    const POSITIVE_INDEX = '1';
    const NEGATIVE_INDEX = '0';

    public function __construct(
        string $query,
        bool $default = true,
        string $positive = null,
        string $negative = null,
        array $routes = []
    )
    {
        $suggestions = [];

        isset($positive) and $suggestions[self::POSITIVE_INDEX] = $positive;
        isset($negative) and $suggestions[self::NEGATIVE_INDEX] = $negative;

        parent::__construct(
            $query,
            $default ? self::POSITIVE_INDEX : self::NEGATIVE_INDEX,
            $suggestions,
            $routes
        );
    }

    public static function stub(): array
    {
        return [
            'query' => '',
            'suggestions' => [
                self::NEGATIVE_INDEX => 'n',
                self::POSITIVE_INDEX => 'y'
            ],
            'routes' => [],
            'default' => null,
        ];
    }


    public static function create(array $data = []): Struct
    {
        return new static(
            $data['query'] ?? '',
            $data['default'] === self::POSITIVE_INDEX,
            $data['suggestions'][self::POSITIVE_INDEX] ?? null,
            $data['suggestions'][self::NEGATIVE_INDEX] ?? null,
            $data['routes'] ?? []
        );
    }

    protected function parseAnswerByMatcher(Cloner $cloner): ? AnswerMsg
    {
        $matcher = $cloner->matcher->refresh();

        if ($matcher->isPositive()) {
            $matcher->refresh();
            return $this->newAnswer($this->_data['suggestions'][self::POSITIVE_INDEX], self::POSITIVE_INDEX);

        } elseif($matcher->isNegative()) {
            $matcher->refresh();
            return $this->newAnswer($this->_data['suggestions'][self::NEGATIVE_INDEX], self::NEGATIVE_INDEX);
        }

        return null;
    }

    /**
     * @param Confirmation $answer
     * @param Comprehension $comprehension
     * @return AnswerMsg
     */
    protected function setAnswerComprehension(AnswerMsg $answer, Comprehension $comprehension): AnswerMsg
    {
        $positive = $answer->isPositive();
        $answer = parent::setAnswerComprehension($answer, $comprehension);


        $comprehension->emotion->setEmotion(EmotionDef::EMO_POSITIVE, $positive);
        $comprehension->emotion->setEmotion(EmotionDef::EMO_NEGATIVE, !$positive);

        return $answer;
    }

    protected function newAnswer(string $answer, string $choice = null): AnswerMsg
    {
        return new IConfirmation([
            'answer' => $answer,
            'choice' => intval($choice) ? self::POSITIVE_INDEX : self::NEGATIVE_INDEX
        ]);
    }

    public function setPositive(string $suggestion, Ucl $ucl = null) : Confirm
    {
        $this->addSuggestion(
            self::POSITIVE_INDEX,
            $suggestion,
            $ucl ? $ucl->toEncodedStr() : null
        );
        return $this;
    }

    public function setNegative(string $suggestion, Ucl $ucl = null) : Confirm
    {
        $this->addSuggestion(
            self::NEGATIVE_INDEX,
            $suggestion,
            $ucl ? $ucl->toEncodedStr() : null
        );
        return $this;
    }

    public function getPositiveSuggestion(): string
    {
        return $this->_data['suggestions'][self::POSITIVE_INDEX] ?? 'y';
    }

    public function getNegativeSuggestion(): string
    {
        return $this->_data['suggestions'][self::NEGATIVE_INDEX] ?? 'n';
    }


    public function getText(): string
    {
        $query = $this->query;
        $p = $this->getPositiveSuggestion();
        $n = $this->getNegativeSuggestion();

        return "$query ($p|$n)";
    }
}