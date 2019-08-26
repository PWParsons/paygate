<?php

namespace PWParsons\PayGate\Foundation\Objects;

class JSONObject
{
    public $resource;
    public $protocol;

    public function __construct(array $data, $protocol = false)
    {
        $this->resource = $data;
        $this->protocol = $protocol;
    }

    public function __call($name, $args)
    {
        if (substr($name, 0, 4) == 'with') {
            $arr = preg_split('/(?=[A-Z])/', substr($name, 4));
            $arr = array_filter($arr);
            $name = strtoupper(implode('_', $arr));

            $this->resource[$name] = $name == 'AMOUNT' ? bcmul($args[0], 100) : $args[0];
        } else {
            if ($this->protocol) {
                return $this->protocol->{$name}($this->resource);
            } else {
                throw new \Exception("You're doing it wrong. Read the documentation.");
            }
        }

        return $this;
    }

    public function gotExpectedResult()
    {
        return (isset($this->resource['meta']) && substr($this->resource['meta']['code'], 0, 1) == '2');
    }

    public function succeeds()
    {
        return $this->gotExpectedResult();
    }

    public function fails()
    {
        return !$this->succeeds();
    }

    public function all()
    {
        return $this->resource;
    }

    public function getErrorCode()
    {
        //
    }

    public function getErrorMessage()
    {
        //
    }
}
