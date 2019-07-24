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
     * @var string
     */
    protected $srcUrl;

    /**
     * AppExtension constructor.
     * @param EntityManagerInterface $em
     * @param EngineInterface $templating
     * @param string $siteKey
     */
    public function __construct(EntityManagerInterface $em, EngineInterface $templating, string $siteKey, string $srcUrl)
    {
        $this->em = $em;
        $this->templating = $templating;
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
            new TwigFunction('google_output_src', [$this, 'outputSrc']),
            new TwigFunction('google_recaptcha_src_url', [$this, 'outputSrcUrl']),
        ];
    }

    /**
     * @return EntityManagerInterface
     */
    public function outputStandardIntegration()
    {
        return $this->templating->render('@SuperrbGoogleRecaptcha/Twig/standard_integration.html.twig', [
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
