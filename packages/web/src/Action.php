<?php
declare(strict_types=1);

namespace Atar\Web;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

interface Action
{

    public function __invoke(RequestInterface $request, VariableCollection $variables): ResponseInterface;

}