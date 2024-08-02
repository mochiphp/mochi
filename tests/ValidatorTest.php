<?php

use PHPUnit\Framework\TestCase;
use Mochi\Validator\Validator;

class ValidatorTest extends TestCase
{
    public function testRequiredFieldWithCustomMessage()
    {
        $validator = new Validator();
        $data = ['name' => ''];
        $rules = [
            'name' => [
                'required' => true,
            ],
            'name.messages' => [
                'required' => 'The name is required.',
            ]
        ];

        $validator->validate($data, $rules);
        $errors = $validator->getErrors();

        $this->assertArrayHasKey('name', $errors);
        $this->assertEquals('The name is required.', $errors['name'][0]);
    }

    public function testRequiredFieldWithoutCustomMessage()
    {
        $validator = new Validator();
        $data = ['name' => ''];
        $rules = [
            'name' => [
                'required' => true,
            ]
        ];

        $validator->validate($data, $rules);
        $errors = $validator->getErrors();

        $this->assertArrayHasKey('name', $errors);
        $this->assertEquals('The name must be filled.', $errors['name'][0]);
    }

    public function testMinLengthWithCustomMessage()
    {
        $validator = new Validator();
        $data = ['username' => 'ab'];
        $rules = [
            'username' => [
                'min' => 3,
            ],
            'username.messages' => [
                'min' => 'The username must be at least 3 characters long.',
            ]
        ];

        $validator->validate($data, $rules);
        $errors = $validator->getErrors();

        $this->assertArrayHasKey('username', $errors);
        $this->assertEquals('The username must be at least 3 characters long.', $errors['username'][0]);
    }

    public function testMinLengthWithoutCustomMessage()
    {
        $validator = new Validator();
        $data = ['username' => 'ab'];
        $rules = [
            'username' => [
                'min' => 3,
            ]
        ];

        $validator->validate($data, $rules);
        $errors = $validator->getErrors();

        $this->assertArrayHasKey('username', $errors);
        $this->assertEquals('The username must be at least 3 characters.', $errors['username'][0]);
    }

    public function testMaxLengthWithCustomMessage()
    {
        $validator = new Validator();
        $data = ['username' => 'abcdefghijk'];
        $rules = [
            'username' => [
                'max' => 10,
            ],
            'username.messages' => [
                'max' => 'The username cannot be longer than 10 characters.',
            ]
        ];

        $validator->validate($data, $rules);
        $errors = $validator->getErrors();

        $this->assertArrayHasKey('username', $errors);
        $this->assertEquals('The username cannot be longer than 10 characters.', $errors['username'][0]);
    }

    public function testMaxLengthWithoutCustomMessage()
    {
        $validator = new Validator();
        $data = ['username' => 'abcdefghijk'];
        $rules = [
            'username' => [
                'max' => 10,
            ]
        ];

        $validator->validate($data, $rules);
        $errors = $validator->getErrors();

        $this->assertArrayHasKey('username', $errors);
        $this->assertEquals('The username must be no more than 10 characters.', $errors['username'][0]);
    }

    public function testValidMinMaxLength()
    {
        $validator = new Validator();
        $data = ['username' => 'abcd'];
        $rules = [
            'username' => [
                'min' => 3,
                'max' => 10,
            ]
        ];

        $validator->validate($data, $rules);
        $errors = $validator->getErrors();

        $this->assertEmpty($errors);
    }

    public function testDataTypeIntegerWithCustomMessage()
    {
        $validator = new Validator();
        $data = ['age' => 'twenty'];
        $rules = [
            'age' => [
                'dataType' => 'integer',
            ],
            'age.messages' => [
                'dataType' => 'The age must be an integer.',
            ]
        ];

        $validator->validate($data, $rules);
        $errors = $validator->getErrors();

        $this->assertArrayHasKey('age', $errors);
        $this->assertEquals('The age must be an integer.', $errors['age'][0]);
    }

    public function testDataTypeIntegerWithoutCustomMessage()
    {
        $validator = new Validator();
        $data = ['age' => 'twenty'];
        $rules = [
            'age' => [
                'dataType' => 'integer',
            ]
        ];

        $validator->validate($data, $rules);
        $errors = $validator->getErrors();

        $this->assertArrayHasKey('age', $errors);
        $this->assertEquals('The age must be of type integer.', $errors['age'][0]);
    }

    public function testDataTypeStringWithCustomMessage()
    {
        $validator = new Validator();
        $data = ['name' => 12345];
        $rules = [
            'name' => [
                'dataType' => 'string',
            ],
            'name.messages' => [
                'dataType' => 'The name must be a string.',
            ]
        ];

        $validator->validate($data, $rules);
        $errors = $validator->getErrors();

        $this->assertArrayHasKey('name', $errors);
        $this->assertEquals('The name must be a string.', $errors['name'][0]);
    }

    public function testDataTypeStringWithoutCustomMessage()
    {
        $validator = new Validator();
        $data = ['name' => 12345];
        $rules = [
            'name' => [
                'dataType' => 'string',
            ]
        ];

        $validator->validate($data, $rules);
        $errors = $validator->getErrors();

        $this->assertArrayHasKey('name', $errors);
        $this->assertEquals('The name must be of type string.', $errors['name'][0]);
    }

    public function testEmailValidationWithCustomMessage()
    {
        $validator = new Validator();
        $data = ['email' => 'invalid-email'];
        $rules = [
            'email' => [
                'email' => true,
            ],
            'email.messages' => [
                'email' => 'The email address is not valid.',
            ]
        ];

        $validator->validate($data, $rules);
        $errors = $validator->getErrors();

        $this->assertArrayHasKey('email', $errors);
        $this->assertEquals('The email address is not valid.', $errors['email'][0]);
    }

    public function testEmailValidationWithoutCustomMessage()
    {
        $validator = new Validator();
        $data = ['email' => 'invalid-email'];
        $rules = [
            'email' => [
                'email' => true,
            ]
        ];

        $validator->validate($data, $rules);
        $errors = $validator->getErrors();

        $this->assertArrayHasKey('email', $errors);
        $this->assertEquals('The email must be a valid email address.', $errors['email'][0]);
    }

    public function testUrlValidationWithCustomMessage()
    {
        $validator = new Validator();
        $data = ['website' => 'invalid-url'];
        $rules = [
            'website' => [
                'url' => true,
            ],
            'website.messages' => [
                'url' => 'The website URL is not valid.',
            ]
        ];

        $validator->validate($data, $rules);
        $errors = $validator->getErrors();

        $this->assertArrayHasKey('website', $errors);
        $this->assertEquals('The website URL is not valid.', $errors['website'][0]);
    }

    public function testUrlValidationWithoutCustomMessage()
    {
        $validator = new Validator();
        $data = ['website' => 'invalid-url'];
        $rules = [
            'website' => [
                'url' => true,
            ]
        ];

        $validator->validate($data, $rules);
        $errors = $validator->getErrors();

        $this->assertArrayHasKey('website', $errors);
        $this->assertEquals('The website must be a valid URL.', $errors['website'][0]);
    }

    public function testDateValidationWithCustomMessage()
    {
        $validator = new Validator();
        $data = ['birthday' => '2024-13-01'];
        $rules = [
            'birthday' => [
                'date' => 'Y-m-d',
            ],
            'birthday.messages' => [
                'date' => 'The birthday is not a valid date.',
            ]
        ];

        $validator->validate($data, $rules);
        $errors = $validator->getErrors();

        $this->assertArrayHasKey('birthday', $errors);
        $this->assertEquals('The birthday is not a valid date.', $errors['birthday'][0]);
    }

    public function testDateValidationWithoutCustomMessage()
    {
        $validator = new Validator();
        $data = ['birthday' => '2024-13-01'];
        $rules = [
            'birthday' => [
                'date' => 'Y-m-d',
            ]
        ];

        $validator->validate($data, $rules);
        $errors = $validator->getErrors();

        $this->assertArrayHasKey('birthday', $errors);
        $this->assertEquals('The birthday must be a valid date in the format Y-m-d.', $errors['birthday'][0]);
    }

    public function testBetweenValidationWithCustomMessage()
    {
        $validator = new Validator();
        $data = ['score' => 15];
        $rules = [
            'score' => [
                'between' => '10|20',
            ],
            'score.messages' => [
                'between' => 'The score must be between 10 and 20.',
            ]
        ];

        $validator->validate($data, $rules);
        $errors = $validator->getErrors();

        $this->assertEmpty($errors);
    }

    public function testBetweenValidationWithoutCustomMessage()
    {
        $validator = new Validator();
        $data = ['score' => 25];
        $rules = [
            'score' => [
                'between' => '10|20',
            ]
        ];

        $validator->validate($data, $rules);
        $errors = $validator->getErrors();

        $this->assertArrayHasKey('score', $errors);
        $this->assertEquals('The score must be no more than 20.', $errors['score'][0]);
    }


    public function testValidBetweenString()
    {
        $validator = new Validator();
        $data = ['username' => 'abcde'];
        $rules = [
            'username' => [
                'between' => '3|10',
            ]
        ];

        $validator->validate($data, $rules);
        $errors = $validator->getErrors();

        $this->assertEmpty($errors);
    }


    public function testInvalidBetweenString()
    {
        $validator = new Validator();
        $data = ['username' => 'abcdefghijk'];
        $rules = [
            'username' => [
                'between' => '3|10',
            ]
        ];

        $validator->validate($data, $rules);
        $errors = $validator->getErrors();

        $this->assertArrayHasKey('username', $errors);
        $this->assertContains('The username must be no more than 10 characters.', $errors['username']);
    }
}
