<?php

namespace Superrb\GoogleRecaptchaBundle\Twig;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Templating\EngineInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * Class AppExtension
 * @package App\Twig
 */
class AppExtension extends AbstractExtension
{
    /**
     * @var EntityManagerInterface
     */
    protected $em;

    /**
     * @var EngineInterface
     */
    protected $templating;

    /**
     * @var string
     */
    protected $siteKey;

    /**
     * AppExtension constructor.
     * @param EntityManagerInterface $em
     * @param EngineInterface $templating
     * @param string $siteKey
     */
    public function __construct(EntityManagerInterface $em, EngineInterface $templating, string $siteKey)
    {
        $this->em = $em;
        $this->templating = $templating;
        $this->siteKey = $siteKey;
    }

    /**
     * @return array
     */
    public function getFunctions()
    {
        return [
            new TwigFunction('google_recaptcha_standard_integration', [$this, 'outputStandardIntegration']),
            new TwigFunction('google_recaptcha_site_key', [$this, 'outputSiteKey']),
        ];
    }

    /**
     * @return EntityManagerInterface
     */
    public function outputStandardIntegration()
    {
        return $this->templating->render('@SuperrbGoogleRecaptcha/Twig/standard_integration.html.twig', [
            'site_key' => $this->siteKey,
        ]);
    }

    /**
     * @return string
     */
    public function outputSiteKey()
    {
        return $this->siteKey;
    }
}
