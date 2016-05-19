<?php

namespace GGG;

class InputValidator
{

  const ALPHA = '/[a-z]+/i';
  const ALPHA_SPACES = '/[a-z\s]+/i';
  const ALPHA_NUMBER = '/[a-z0-9]+/i';
  const ALPHA_NUMBER_SPACES = '/[a-z0-9\s]+/i';
  const ALPHA_SYMBOL = '/[a-z\W]+/i';
  const ALPHA_SYMBOL_SPACES = '/[a-z\W\s]+/i';
  const ALPHA_NUMBER_SYMBOL = '/[a-z0-9\W]+/i';
  const ALPHA_NUMBER_SYMBOL_SPACES = '/[a-z0-9\W\s]+/i';
  const NUMBER_STRICT = '/[0-9]+/';
  const NUMBER_SPACES = '/[0-9\s]+/';
  const SYMBOL_STRICT = '/[\W]+/';
  const SYMBOL_SPACES = '/[\W\s]+/';
  const NUMBER_SYMBOL = '/[0-9\W]+/';
  const NUMBER_SYMBOL_SPACES = '/[0-9\W\s]+/';
  const PHONE = '/(\+?\d[\-\.\(]?[\d]{3}[\-\.\)]?[\d]{3}[\-\.]?[\d]{4})/';
  const BOOL_STRICT = '/((T|t)|rue?)|((F|f)|alse?)/';
  const BINARY_STRICT = '/[01]/';
  const POST = 'POST';
  const GET = 'GET';
  protected $method;
  protected $inputs;
  protected $rules;

  public function __construct( $method = 'POST', $rules = array() )
  {
    $this->method = $method === 'POST' ? static::POST : static::GET;
    foreach( $rules as $index => $rule ) {
      $this->rules[ $index ]['rule'] = strtoupper( $rule[0] );
      if( is_array( $rule[1] ) ) {
        $this->rules[ $index ]['length']['min'] = $rule[1][0];
        $this->rules[ $index ]['length']['max'] = $rule[1][1];
      } else {
        $this->rules[ $index ]['length'] = $rule[1];
      }
    }
    $this->loadInputs();
    return $this;
  }

  private function loadInputs()
  {
    if( $this->method === static::GET ) {
      if( empty( $_GET ) ) {
        $this->inputs = NULL;
      } else {
        $this->inputs = $_GET;
      }
    }
    if( $this->method === static::POST ) {
      if( empty( $_POST ) ) {
        $this->inputs = NULL;
      } else {
        $this->inputs = $_POST;
      }
    }
  }

  public function validate()
  {
    $result = [];
    $index = -1;
    if( $this->inputs === NULL ) {
      $result[] .= 'No ' . $this->method . ' parameters have been provided nor set.';
    } else {
      foreach( $this->inputs as $indexA => $input ) {
        $index++;
        $pattern = $this->rules[ $index ]['rule'];
        $pattern = constant("static::{$pattern}");
        if( preg_match( $pattern, $input ) ) {
          if( is_array( $this->rules[ $index ]['length'] ) ) {
            if( strlen( $input ) >= $this->rules[ $index ]['length']['min'] ) {
              if( strlen( $input ) <= $this->rules[ $index ]['length']['max'] ) {
                next( $this->inputs );
              } else {
                $result[ $index ] = 'Input ' . $indexA . ' cannot be longer than ' . $this->rules[ $index ]['length']['max'] . ' characters.';
              }
            } else {
              $result[ $index ] = 'Input ' . $indexA . ' cannot be shorter than ' . $this->rules[ $index ]['length']['min'] . ' characters.';
            }
          }
          if( (! is_array( $this->rules[ $index ]['length'] ) ) && ( $this->rules[ $index ]['length'] === 0 || is_null( $this->rules[ $index ]['length'] ) ) ) {
            next( $this->inputs );
          }
        } else {
          $result[ $index ] = ucfirst( $indexA ) . ' input value (' . $input . ') did not pass character validation on ' . $this->rules[ $index ]['rule'] . ' pattern test.'; 
        }
      }
    }
    if( count( $result ) > 0 ) {
      return $result;
    } else {
      return true;
    }
  }

  public function getInputs()
  {
    return $this->inputs;
  }

}