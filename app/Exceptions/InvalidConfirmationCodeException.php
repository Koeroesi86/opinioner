<?php
namespace App\Exceptions;

class InvalidConfirmationCodeException extends \Exception {
    protected $message = "Confirmation code not found.";
}