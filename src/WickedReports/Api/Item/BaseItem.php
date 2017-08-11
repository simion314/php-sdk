<?php

namespace WickedReports\Api\Item;

use JsonSerializable;
use Respect\Validation\Exceptions\NestedValidationException;
use WickedReports\Exception\ValidationException;

abstract class BaseItem implements JsonSerializable {

    /**
     * Item data array
     * @var array
     */
    private $data = [];

    /**
     * BaseItem constructor.
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Get field directly
     * @param string $name
     * @return mixed|null
     */
    public function __get($name)
    {
        return isset($this->data[$name]) ? $this->data[$name] : null;
    }

    /**
     * Get item data
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Set new item data
     * @param array $data
     */
    public function setData(array $data)
    {
        $this->data = $data;
    }

    /**
     * Check whether item has some data in it
     * @return bool
     */
    public function isFilled()
    {
        return ! empty($this->data);
    }

    /**
     * @return bool
     * @throws ValidationException
     */
    public function validate()
    {
        $validation = $this->validation();

        if ( ! $validation instanceof \Respect\Validation\Validator) {
            throw new ValidationException('Validation should be instance of \Respect\Validation\Validator');
        }

        try {
            $validation->assert($this->getData());
        }
        catch (NestedValidationException $e) {
            throw new ValidationException(implode("\n", $e->getMessages()), 0, $e);
        }

        return true;
    }

    /**
     * @return array
     */
    public function jsonSerialize ()
    {
        return $this->data;
    }

    /**
     * @return string
     */
    public function toJson()
    {
        return json_encode($this);
    }

    /**
     * Validation must be declared here
     */
    abstract protected function validation();

}