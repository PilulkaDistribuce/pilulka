<?php
declare(strict_types=1);

namespace Atar\Web;

interface LinkWebalizer
{

    public function webalize($input): string;

}