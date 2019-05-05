<?php
declare(strict_types=1);

namespace Atar\Web;


use Webmozart\Assert\Assert;

class VariableCollection
{
    /**
     * @var array
     */
    private $variables;


    /**
     * VariableCollection constructor.
     */
    public function __construct(array $variables = [])
    {
        $this->variables = $variables;
    }

    public function get(string $key)
    {
        Assert::keyExists($this->variables, $key);
        return $this->variables[$key];
    }

}