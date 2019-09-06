<?php

namespace PWParsons\PayGate\Foundation\Objects;

class JSONObject
{
    /**
     * Container for the object data.
     *
     * @var array
     */
    public $resource;

    /**
     * Container for the API protocol/service instantiation.
     *
     * @var mixed
     */
    public $protocol;

    public function __construct(array $data, $protocol = false)
    {
        $this->resource = $data;
        $this->protocol = $protocol;
    }

    /**
     * Returns true if the expected JSON result is found.
     *
     * @return boolean
     */
    public function gotExpectedResult()
    {
        return (isset($this->resource['meta']) && !array_key_exists('ERROR', $this->resource['meta']));
    }

    /**
     * An alias for gotExpectedResult().
     *
     * @return boolean
     */
    public function succeeds()
    {
        return $this->gotExpectedResult();
    }

    /**
     * Returns true if the expected JSON result is not found.
     *
     * @return boolean
     */
    public function fails()
    {
        return !$this->succeeds();
    }

    /**
     * Retreive the PayGate error code.
     *
     * @return int
     */
    public function getErrorCode()
    {
        return $this->fails() ? $this->resource['meta']['ERROR'] : '';
    }

    /**
     * Retreive the PayGate error message.
     *
     * @return string
     */
    public function getErrorMessage()
    {
        return $this->fails() ? $this->resource['meta']['ERROR_MESSAGE'] : '';
    }

    /**
     * Magic method that catches undefined functions
     * looks for custom magic method and returns the
     * result.
     *
     * An exception is thrown is the function does not exist
     * and it is not a magic method.
     *
     * @return mixed
     */
    public function __call($name, $args)
    {
        if (substr($name, 0, 4) == 'with') {
            $arr = preg_split('/(?=[A-Z])/', substr($name, 4));
            $arr = array_filter($arr);
            $name = strtoupper(implode('_', $arr));

            $this->resource['data'][$name] = $name == 'AMOUNT' ? bcmul($args[0], 100) : $args[0];
        } else {
            if ($this->protocol) {
                return $this->protocol->{$name}($this->resource);
            } else {
                throw new \Exception("You're doing it wrong. Read the documentation.");
            }
        }

        return $this;
    }

    /**
     * Returns the entire resource object
     *
     * @return mixed
     */
    public function all()
    {
        return $this->resource;
    }
}
