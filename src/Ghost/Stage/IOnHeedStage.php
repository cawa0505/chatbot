<?php

/**
 * This file is part of CommuneChatbot.
 *
 * @link     https://github.com/thirdgerb/chatbot
 * @document https://github.com/thirdgerb/chatbot/blob/master/README.md
 * @contact  <thirdgerb@gmail.com>
 * @license  https://github.com/thirdgerb/chatbot/blob/master/LICENSE
 */

namespace Commune\Ghost\Stage;

use Commune\Blueprint\Ghost\Callables\Operating;
use Commune\Blueprint\Ghost\Operator\Operator;
use Commune\Blueprint\Ghost\Routing\Backward;
use Commune\Blueprint\Ghost\Routing\Fallback;
use Commune\Blueprint\Ghost\Routing\Hearing;
use Commune\Blueprint\Ghost\Routing\Staging;
use Commune\Blueprint\Ghost\Stage\OnHeed;
use Commune\Ghost\Operators\End\NoStateEnd;
use Commune\Ghost\Routing\IBackward;
use Commune\Ghost\Routing\IFallback;
use Commune\Ghost\Routing\IHearing;
use Commune\Ghost\Routing\IStaging;


/**
 * @author thirdgerb <thirdgerb@gmail.com>
 */
class IOnHeedStage extends AStage implements OnHeed
{
    public function end(): Operator
    {
        return $this->hearing()->end();
    }

    public function privateEnd(): Operator
    {
        return $this->hearing()->privateEnd();
    }

    public function dumb(): Operator
    {
        return new NoStateEnd();
    }

    public function confuse(): Operator
    {
        // TODO: Implement confuse() method.
    }

    public function hearing(): Hearing
    {
        return new IHearing($this);
    }

    public function staging(): Staging
    {
        return new IStaging($this);
    }

    public function fallback(): Fallback
    {
        return new IFallback($this);
    }

    public function backward(): Backward
    {
        return new IBackward();
    }


}