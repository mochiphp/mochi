<?php

namespace Mochi\Validator;

class Validator
{
    private $errors = [];

    public function validate($data, $rules)
    {

        foreach ($rules as $field => $fieldRules) {
            foreach ($fieldRules as $rule => $ruleValue) {
                $value = isset($data[$field]) ? $data[$field] : null;
                $method = 'validate' . ucfirst($rule);

                if (method_exists($this, $method)) {
                    $this->$method($field, $value, $ruleValue);
                }
            }
        }
    }

    public function getErrors()
    {
        return $this->errors;
    }

    private function addError($field, $message)
    {
        $this->errors[$field][] = $message;
    }

    private function validateMin($field, $value, $min)
    {

        if (is_numeric($value)) {
            if (floatval($value) < floatval($min)) {
                $this->addError($field, "The $field must be at least $min.");
            }
        } elseif (is_string($value) && strlen($value) < $min) {
            $this->addError($field, "The $field must be at least $min characters.");
        }
    }

    private function validateMax($field, $value, $max)
    {

        if (is_numeric($value)) {
            if (floatval($value) > floatval($max)) {
                $this->addError($field, "The $field must be no more than $max.");
            }
        } elseif (is_string($value) && strlen($value) > $max) {
            $this->addError($field, "The $field must be no more than $max characters.");
        }
    }

    private function validateEmail($field, $value)
    {
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $this->addError($field, "The $field must be a valid email address.");
        }
    }

    // TODO: Write TEST
    private function validateUrl($field, $value)
    {
        if (!filter_var($value, FILTER_VALIDATE_URL)) {
            $this->addError($field, "The $field must be a valid email address.");
        }
    }

    private function validateRequired($field, $value)
    {
        if (empty($value)) {
            $this->addError($field, "The $field must be filled.");
        }
    }

    private function validateDataType($field, $value, $dataType)
    {
        $types = [
            'integer'   => 'is_int',
            'float'     => 'is_float',
            'string'    => 'is_string',
            'boolean'   => 'is_bool',
            'array'     => 'is_array',
            'object'    => 'is_object',
            'null'      => 'is_null',
        ];

        if (!isset($types[$dataType])) {
            throw new \InvalidArgumentException("Unknown data type: $dataType");
        }

        $typeCheckFunction = $types[$dataType];

        if (!$typeCheckFunction($value)) {
            $this->addError($field, "The $field must be of type $dataType.");
        }
    }

    private function validateDate($field, $value, $format = 'Y-m-d')
    {
        $date = \DateTime::createFromFormat($format, $value);
        if ($date === false || $date->format($format) !== $value) {
            $this->addError($field, "The $field must be a valid date in the format $format.");
        }
    }

    // TODO: Unit Testing
    private function validateBetween($field, $value, $beetween)
    {
        list($min, $max) = explode("|", $beetween);
        if (is_numeric($min) && is_numeric($max)) {
            if (floatval($value) > floatval($min)) $this->addError($field, "The $field must be at least $min.");
            if (floatval($value) < floatval($max)) $this->addError($field, "The $field must be no more than $max.");
        } elseif (is_string($value) && strlen($value) > $max) {
            $this->addError($field, "The $field must be no more than $max characters.");
        } elseif (is_string($value) && strlen($value) > $max) {
            $this->addError($field, "The $field must be no more than $max characters.");
        }
    }


    public function validateInput($input, $rules)
    {
        $validator = new Validator();

        $data = json_decode($input, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            parse_str($input, $data);
        }

        $validator->validate($data, $rules);

        return $validator->getErrors();
    }
}
