<?php


namespace Commune\Chatbot\App\Messages\Media;

use Commune\Chatbot\Framework\Messages\AbsMedia;
use Commune\Chatbot\Blueprint\Message\Media\ImageMsg;

class Image extends AbsMedia implements ImageMsg
{
    public function namesAsDependency(): array
    {
        $names = parent::namesAsDependency();
        $names[] = ImageMsg::class;
        return $names;
    }

}