<?php

namespace PWParsons\PayGate\Foundation\Objects;

class JSONObject
{
    public array $resource;

    public $protocol;

    public function __construct(array $data, $protocol = false)
    {
        $this->resource = $data;
        $this->protocol = $protocol;
    }

    public function gotExpectedResult(): bool
    {
        return isset($this->resource['meta'])
            && ! array_key_exists('ERROR_CODE', $this->resource['meta']);
    }

    public function succeeds(): bool
    {
        return $this->gotExpectedResult();
    }

    public function fails(): bool
    {
        return ! $this->succeeds();
    }

    /**
     * Magic method that catches undefined functions looks for custom magic
     * method and returns the result. An exception is thrown if the function
     * does not exist and it is not a magic method.
     *
     * @param string $name
     * @param $args
     * @return $this
     * @throws \Exception
     */
    public function __call(string $name, $args)
    {
        if (substr($name, 0, 4) == 'with') {
            $arr = preg_split('/(?=[A-Z])/', substr($name, 4));
            $arr = array_filter($arr);
            $name = strtoupper(implode('_', $arr));

            $this->resource['data'][$name] = $name == 'AMOUNT'
                ? bcmul($args[0], '100')
                : $args[0];
        } elseif (substr($name, 0, 3) == 'get') {
            $arr = preg_split('/(?=[A-Z])/', substr($name, 3));
            $arr = array_filter($arr);
            $name = strtoupper(implode('_', $arr));

            return $this->resource['meta'][$name];
        } else {
            if ($this->protocol) {
                return $this->protocol->{$name}($this->resource);
            } else {
                throw new \Exception("You're doing it wrong. Read the documentation.");
            }
        }

        return $this;
    }

    public function all(): array
    {
        return $this->resource;
    }
}
