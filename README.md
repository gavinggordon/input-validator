# InputValidator v1.0.0

[![Build Status](https://travis-ci.org/gavinggordon/input-validator.svg?branch=master)](https://travis-ci.org/gavinggordon/input-validator)

--------------

## Prologue

Quickly define and run validation check on user data provided upon either GET or POST requests.

--------------

### Installation (via Composer)

    composer require gavinggordon/input-validator

### Include autoloader.php

    include_once( __DIR__ . '/vendor/autoload.php' );

### Examples

#### Set Rules

The rules array contains the validation rules for each input.
Each input is equal to 1 array, and, in itself, contains the rules for its own validation. 

The first parameter of an input's validation array is used to define the Regex pattern by which to validate its value against.

The second parameter, which is also an array, is used to define the string length of the value, noted by the first parameter as a minimum length, followed by a maximum length.
```
$rules = [
  [
    'alpha', [ 5,16 ]
  ],
  [
    'phone', [ 7,15 ]
  ],
  [
    'alpha_number_symbol_spaces', [ 2,155 ]
  ]
];
```

#### Create Instance

To instantiate the class, two arguments are required:

The first argument must be a string value of either 'GET' or 'POST', and is used to determine the request type being validated.

The second argument must be an array of rules, as mentioned in the above example.
```
$validator = new GGG\InputValidator( 'POST', $rules );
```

#### Run the Validator

```
$validated = $validator->validate();
```

#### Determine if Validated

```
// if passed validation 
if( $validated === true ) {

  // retrieve an associative array of inputs
  $data = $validator->getInputs();
  
}

// if has errors 
if( is_array( $validated ) ) {

  // show an array containing messages for
  // each input which did not pass validation.
  var_dump( $validated );
  
}
```    

--------------

#### More Information

##### Feedback

If you found this class to be at all interesting, helpful, particularly useful, or innovative in any way, check out my other PHP classes  via my [GitHub profile](https://github.com/gavinggordon) or [PHPClasses.org profile](http://www.phpclasses.org/browse/author/1348645.html).

--------------
