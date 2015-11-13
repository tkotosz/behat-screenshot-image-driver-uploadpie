<?php

namespace Bex\Behat\ScreenshotExtension\Driver;

use Bex\Behat\ScreenshotExtension\Driver\ImageDriverInterface;
use Bex\Behat\ScreenshotExtension\Driver\Service\UploadPieApi;
use Buzz\Client\Curl;
use Buzz\Message\Form\FormRequest;
use Buzz\Message\Form\FormUpload;
use Buzz\Message\Response;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class UploadPie implements ImageDriverInterface
{
    const CONFIG_PARAM_EXPIRE = 'expire';

    /**
     * @var array
     */
    private $expireMapping = ['30m' => 1, '1h' => 2, '6h' => 3, '1d' => 4, '1w' => 5];

    /**
     * @var UploadPieApi
     */
    private $api;

    /**
     * @var int
     */
    private $expire;

    /**
     * @param UploadPieApi|null $api
     */
    public function __construct(UploadPieApi $api = null)
    {
        $this->api = $api ?: new UploadPieApi();
    }

    /**
     * @param  ArrayNodeDefinition $builder
     */
    public function configure(ArrayNodeDefinition $builder)
    {
        $builder
            ->children()
                ->enumNode(self::CONFIG_PARAM_EXPIRE)
                    ->values(array('30m', '1h', '6h', '1d', '1w'))
                    ->defaultValue('30m')
                ->end()
            ->end();
    }

    /**
     * @param  ContainerBuilder $container
     * @param  array            $config
     */
    public function load(ContainerBuilder $container, array $config)
    {
        $this->expire = $this->convertExpireValue($config[self::CONFIG_PARAM_EXPIRE]);
    }

    /**
     * @param string $binaryImage
     * @param string $filename
     *
     * @return string URL to the image
     */
    public function upload($binaryImage, $filename)
    {
        return $this->api->call($binaryImage, $filename, $this->expire);
    }

    /**
     * @param  string $value
     *
     * @return int
     */
    private function convertExpireValue($expire)
    {
        return $this->expireMapping[$expire];
    }
}