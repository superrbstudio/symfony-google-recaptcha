<?php

namespace Superrb\GoogleRecaptchaBundle\Validator\Constraint;

use Superrb\GoogleRecaptchaBundle\Service\GoogleRecaptchaService;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class GoogleRecaptchaValidator extends ConstraintValidator
{
    /**
     * @var GoogleRecaptchaService
     */
    private $googleRecaptcha;

    /**
     * GoogleRecaptchaValidator constructor.
     * @param GoogleRecaptchaService $googleRecaptcha
     */
    public function __construct(GoogleRecaptchaService $googleRecaptcha)
    {
        $this->setGoogleRecaptcha($googleRecaptcha);
    }

    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof GoogleRecaptcha) {
            throw new UnexpectedTypeException($constraint, GoogleRecaptcha::class);
        }

        // custom constraints should ignore null and empty values to allow
        // other constraints (NotBlank, NotNull, etc.) take care of that
        if (null === $value || '' === $value) {
            return;
        }

        if (!is_string($value)) {
            // throw this exception if your validator cannot handle the passed type so that it can be marked as invalid
            throw new UnexpectedValueException($value, 'string');

            // separate multiple types using pipes
            // throw new UnexpectedValueException($value, 'string|int');
        }

        // validate the value
        if (!$this->getGoogleRecaptcha()->validateToken($value)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ string }}', $value)
                ->addViolation();
        }
    }

    /**
     * @return GoogleRecaptchaService
     */
    public function getGoogleRecaptcha(): GoogleRecaptchaService
    {
        return $this->googleRecaptcha;
    }

    /**
     * @param GoogleRecaptchaService $googleRecaptcha
     */
    public function setGoogleRecaptcha(GoogleRecaptchaService $googleRecaptcha): void
    {
        $this->googleRecaptcha = $googleRecaptcha;
    }
}