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

        // validate the value
        // consider empty values as a failure
        if (null === $value || '' === $value || !$this->getGoogleRecaptcha()->validateToken($value)) {
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
