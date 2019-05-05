<?php
declare(strict_types=1);

namespace Atar\Web\Link;

use Atar\Web\LinkWebalizer;

class RawWebalizer implements LinkWebalizer
{
    public function webalize($input): string
    {
        return (string)$input;
    }
}
