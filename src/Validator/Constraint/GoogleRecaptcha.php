<?php

namespace Superrb\GoogleRecaptchaBundle\Validator\Constraint;

use Symfony\Component\Validator\Constraint;

class GoogleRecaptcha extends Constraint
{
    /**
     * @var string
     * @Annotation
     */
    public $message = 'You don\'t appear to be a human, please try again';

    /**
     * @return string
     */
    public function validatedBy()
    {
        return \get_class($this).'Validator';
    }
}