<?php

namespace Superrb\GoogleRecaptchaBundle\Twig;

use Doctrine\ORM\EntityManagerInterface;
use Twig\Environment;
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
     * @var Environment
     */
    protected $twig;

    /**
     * @var string
     */
    protected $siteKey;

    /**
     * @var string
     */
    protected $srcUrl;

    /**
     * AppExtension constructor.
     * @param EntityManagerInterface $em
     * @param Environment $twig
     * @param string $siteKey
     */
    public function __construct(EntityManagerInterface $em, Environment $twig, string $siteKey, string $srcUrl)
    {
        $this->em = $em;
        $this->twig = $twig;
        $this->siteKey = $siteKey;
        $this->srcUrl = $srcUrl;
    }

    /**
     * @return array
     */
    public function getFunctions()
    {
        return [
            new TwigFunction('google_recaptcha_standard_integration', [$this, 'outputStandardIntegration']),
            new TwigFunction('google_recaptcha_site_key', [$this, 'outputSiteKey']),
            new TwigFunction('google_recaptcha_output_src', [$this, 'outputSrc']),
            new TwigFunction('google_recaptcha_src_url', [$this, 'outputSrcUrl']),
        ];
    }

    /**
     * @return EntityManagerInterface
     */
    public function outputStandardIntegration()
    {
        return $this->twig->render('@SuperrbGoogleRecaptcha/Twig/standard_integration.html.twig', [
            'site_key' => $this->siteKey,
            'src_url' => $this->srcUrl,
        ]);
    }

    public function outputSrc()
    {
        return '<script src="' . $this->outputSrcUrl() . '"></script>';
    }

    /**
     * @return string
     */
    public function outputSrcUrl()
    {
        return $this->srcUrl;
    }

    /**
     * @return string
     */
    public function outputSiteKey()
    {
        return $this->siteKey;
    }
}
