<?php

use PHPUnit\Framework\TestCase;
use Mochi\Validator\Validator;

class ValidatorTest extends TestCase
{
    private $validator;

    protected function setUp(): void
    {
        $this->validator = new Validator();
    }

    public function testValidateMin()
    {
        $data = ['username' => 'us'];
        $rules = ['username' => ['min' => [3, 'message' => 'The username must be at least 3 characters.']]];

        $this->validator->validate($data, $rules);
        $errors = $this->validator->getErrors();

        $this->assertArrayHasKey('username', $errors);
        $this->assertContains('The username must be at least 3 characters.', $errors['username']);
    }

    public function testValidateMinValid()
    {
        $data = ['username' => 'user'];
        $rules = ['username' => ['min' => [3, 'message' => 'The username must be at least 3 characters.']]];

        $this->validator->validate($data, $rules);
        $errors = $this->validator->getErrors();

        $this->assertArrayNotHasKey('username', $errors);
    }

    public function testValidateMax()
    {
        $data = ['username' => 'this_is_a_very_long_username'];
        $rules = ['username' => ['max' => [10, 'message' => 'The username must be no more than 10 characters.']]];

        $this->validator->validate($data, $rules);
        $errors = $this->validator->getErrors();

        $this->assertArrayHasKey('username', $errors);
        $this->assertContains('The username must be no more than 10 characters.', $errors['username']);
    }

    public function testValidateMaxValid()
    {
        $data = ['username' => 'user'];
        $rules = ['username' => ['max' => [10, 'message' => 'The username must be no more than 10 characters.']]];

        $this->validator->validate($data, $rules);
        $errors = $this->validator->getErrors();

        $this->assertArrayNotHasKey('username', $errors);
    }

    public function testValidateEmail()
    {
        $data = ['email' => 'invalid-email'];
        $rules = ['email' => ['email' => [true, 'message' => 'The email must be a valid email address.']]];

        $this->validator->validate($data, $rules);
        $errors = $this->validator->getErrors();

        $this->assertArrayHasKey('email', $errors);
        $this->assertContains('The email must be a valid email address.', $errors['email']);
    }

    public function testValidateEmailValid()
    {
        $data = ['email' => 'valid@example.com'];
        $rules = ['email' => ['email' => [true, 'message' => 'The email must be a valid email address.']]];

        $this->validator->validate($data, $rules);
        $errors = $this->validator->getErrors();

        $this->assertArrayNotHasKey('email', $errors);
    }

    public function testValidateInputWithJson()
    {
        $input = '{"email": "invalid-email", "username": "user"}';
        $rules = [
            'email' => ['email' => [true, 'message' => 'The email must be a valid email address.']],
            'username' => ['min' => [3, 'message' => 'The username must be at least 3 characters.'], 'max' => [15, 'message' => 'The username must be no more than 15 characters.']]
        ];

        $errors = $this->validator->validateInput($input, $rules);

        $this->assertArrayHasKey('email', $errors);
        $this->assertContains('The email must be a valid email address.', $errors['email']);
    }

    public function testValidateInputWithJsonValid()
    {
        $input = '{"email": "valid@example.com", "username": "user"}';
        $rules = [
            'email' => ['email' => [true, 'message' => 'The email must be a valid email address.']],
            'username' => ['min' => [3, 'message' => 'The username must be at least 3 characters.'], 'max' => [15, 'message' => 'The username must be no more than 15 characters.']]
        ];

        $errors = $this->validator->validateInput($input, $rules);

        $this->assertEmpty($errors);
    }

    public function testValidateInputWithForm()
    {
        $input = "email=invalid-email&username=user";
        $rules = [
            'email' => ['email' => [true, 'message' => 'The email must be a valid email address.']],
            'username' => ['min' => [3, 'message' => 'The username must be at least 3 characters.'], 'max' => [15, 'message' => 'The username must be no more than 15 characters.']]
        ];

        $errors = $this->validator->validateInput($input, $rules);

        $this->assertArrayHasKey('email', $errors);
        $this->assertContains('The email must be a valid email address.', $errors['email']);
    }

    public function testValidateInputWithFormValid()
    {
        $input = "email=valid@example.com&username=user";
        $rules = [
            'email' => ['email' => [true, 'message' => 'The email must be a valid email address.']],
            'username' => ['min' => [3, 'message' => 'The username must be at least 3 characters.'], 'max' => [15, 'message' => 'The username must be no more than 15 characters.']]
        ];

        $errors = $this->validator->validateInput($input, $rules);

        $this->assertEmpty($errors);
    }

    public function testValidateRequired()
    {
        $input = "email=invalid-email&username=";
        $rules = [
            'email' => ['email' => [true, 'message' => 'The email must be a valid email address.']],
            'username' => ['required' => [true, 'message' => 'The username must be filled.'], 'min' => [3, 'message' => 'The username must be at least 3 characters.'], 'max' => [15, 'message' => 'The username must be no more than 15 characters.']]
        ];

        $errors = $this->validator->validateInput($input, $rules);

        $this->assertArrayHasKey('username', $errors);
        $this->assertContains('The username must be filled.', $errors['username']);
    }

    public function testValidateRequiredValid()
    {
        $input = "email=valid@example.com&username=user";
        $rules = [
            'email' => ['email' => [true, 'message' => 'The email must be a valid email address.']],
            'username' => ['required' => [true, 'message' => 'The username must be filled.'], 'min' => [3, 'message' => 'The username must be at least 3 characters.'], 'max' => [15, 'message' => 'The username must be no more than 15 characters.']]
        ];

        $errors = $this->validator->validateInput($input, $rules);

        $this->assertEmpty($errors);
    }

    public function testValidInteger()
    {
        $data = [
            'age' => 30,
        ];

        $rules = [
            'age' => [
                'dataType' => ['integer', 'message' => 'The age must be of type integer.'],
                'min' => [18, 'message' => 'The age must be at least 18.'],
            ],
        ];

        $this->validator->validate($data, $rules);
        $errors = $this->validator->getErrors();

        $this->assertEmpty($errors, 'No errors should be present for a valid integer.');
    }

    public function testInvalidInteger()
    {
        $data = [
            'age' => 'thirty',
        ];

        $rules = [
            'age' => [
                'dataType' => ['integer'],
            ],
        ];

        $this->validator->validate($data, $rules);
        $errors = $this->validator->getErrors();

        $this->assertNotEmpty($errors, 'An error should be present for an invalid integer.');
        $this->assertArrayHasKey('age', $errors, 'The errors array should contain an entry for "age".');
        $this->assertContains('The age must be of type integer.', $errors['age'], 'The error message should indicate the wrong type.');
    }

    public function testValidFloat()
    {
        $data = [
            'price' => 19.99,
        ];

        $rules = [
            'price' => [
                'dataType' => ['float', 'message' => 'The price must be of type float.'],
            ],
        ];

        $this->validator->validate($data, $rules);
        $errors = $this->validator->getErrors();

        $this->assertEmpty($errors, 'No errors should be present for a valid float.');
    }

    public function testInvalidFloat()
    {
        $data = [
            'price' => 'not_a_float',
        ];

        $rules = [
            'price' => [
                'dataType' => ['float', 'message' => 'The price must be of type float.'],
            ],
        ];

        $this->validator->validate($data, $rules);
        $errors = $this->validator->getErrors();

        $this->assertNotEmpty($errors, 'An error should be present for an invalid float.');
        $this->assertArrayHasKey('price', $errors, 'The errors array should contain an entry for "price".');
        $this->assertContains('The price must be of type float.', $errors['price'], 'The error message should indicate the wrong type.');
    }

    public function testValidString()
    {
        $data = [
            'name' => 'John Doe',
        ];

        $rules = [
            'name' => [
                'dataType' => ['string', 'message' => 'The name must be of type string.'],
                'min' => [3, 'message' => 'The name must be at least 3 characters.'],
            ],
        ];

        $this->validator->validate($data, $rules);
        $errors = $this->validator->getErrors();

        $this->assertEmpty($errors, 'No errors should be present for a valid string.');
    }

    public function testInvalidString()
    {
        $data = [
            'name' => 123,
        ];

        $rules = [
            'name' => [
                'dataType' => ['string', 'message' => 'The name must be of type string.'],
                'min' => [3, 'message' => 'The name must be at least 3 characters.'],
            ],
        ];

        $this->validator->validate($data, $rules);
        $errors = $this->validator->getErrors();

        $this->assertNotEmpty($errors, 'An error should be present for an invalid string.');
        $this->assertArrayHasKey('name', $errors, 'The errors array should contain an entry for "name".');
        $this->assertContains('The name must be of type string.', $errors['name'], 'The error message should indicate the wrong type.');
    }

    public function testValidBoolean()
    {
        $data = [
            'isActive' => true,
        ];

        $rules = [
            'isActive' => [
                'dataType' => ['boolean', 'message' => 'The isActive must be of type boolean.'],
            ],
        ];

        $this->validator->validate($data, $rules);
        $errors = $this->validator->getErrors();

        $this->assertEmpty($errors, 'No errors should be present for a valid boolean.');
    }

    public function testInvalidBoolean()
    {
        $data = [
            'isActive' => 'true',
        ];

        $rules = [
            'isActive' => [
                'dataType' => ['boolean', 'message' => 'The isActive must be of type boolean.'],
            ],
        ];

        $this->validator->validate($data, $rules);
        $errors = $this->validator->getErrors();

        $this->assertNotEmpty($errors, 'An error should be present for an invalid boolean.');
        $this->assertArrayHasKey('isActive', $errors, 'The errors array should contain an entry for "isActive".');
        $this->assertContains('The isActive must be of type boolean.', $errors['isActive'], 'The error message should indicate the wrong type.');
    }

    public function testValidNull()
    {
        $data = [
            'profile' => null,
        ];

        $rules = [
            'profile' => [
                'dataType' => ['null', 'message' => 'The profile must be of type null.'],
            ],
        ];

        $this->validator->validate($data, $rules);
        $errors = $this->validator->getErrors();

        $this->assertEmpty($errors, 'No errors should be present for a valid null value.');
    }

    public function testInvalidNull()
    {
        $data = [
            'profile' => 'not_null',
        ];

        $rules = [
            'profile' => [
                'dataType' => ['null', 'message' => 'The profile must be of type null.'],
            ],
        ];

        $this->validator->validate($data, $rules);
        $errors = $this->validator->getErrors();

        $this->assertNotEmpty($errors, 'An error should be present for an invalid null value.');
        $this->assertArrayHasKey('profile', $errors, 'The errors array should contain an entry for "profile".');
        $this->assertContains('The profile must be of type null.', $errors['profile'], 'The error message should indicate the wrong type.');
    }

    public function testValidDate()
    {
        $data = [
            'event_date' => '2024-07-29',
        ];

        $rules = [
            'event_date' => [
                'date' => ['Y-m-d', 'message' => 'The event_date must be a valid date in the format Y-m-d.'],
            ],
        ];

        $this->validator->validate($data, $rules);
        $errors = $this->validator->getErrors();

        // Ensure no validation errors are returned
        $this->assertEmpty($errors, 'No errors should be present for a valid date.');
    }

    public function testInvalidDate()
    {
        $data = [
            'event_date' => '2024-02-30',
        ];

        $rules = [
            'event_date' => [
                'date' => ['Y-m-d', 'message' => 'The event_date must be a valid date in the format Y-m-d.'],
            ],
        ];

        $this->validator->validate($data, $rules);
        $errors = $this->validator->getErrors();

        // Ensure there is a validation error
        $this->assertNotEmpty($errors);
        $this->assertArrayHasKey('event_date', $errors);
        $this->assertContains('The event_date must be a valid date in the format Y-m-d.', $errors['event_date']);
    }

    public function testValidateUrl()
    {
        $data = ['website' => 'invalid-url'];
        $rules = ['website' => ['url' => [true, 'message' => 'The website must be a valid URL.']]];
        $this->validator->validate($data, $rules);
        $errors = $this->validator->getErrors();
        $this->assertArrayHasKey('website', $errors);
        $this->assertEquals('The website must be a valid URL.', $errors['website'][0]);
    }

    public function testValidateBetween()
    {
        $data = ['age' => 15];
        $rules = ['age' => ['between' => ['18|25', 'message' => 'The age must be at least 18.']]];
        $this->validator->validate($data, $rules);
        $errors = $this->validator->getErrors();
        $this->assertArrayHasKey('age', $errors);
        $this->assertEquals('The age must be at least 18.', $errors['age'][0]);
    }
}
