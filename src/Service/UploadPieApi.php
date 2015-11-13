<?php

namespace Bex\Behat\ScreenshotExtension\Driver\Service;

use Buzz\Client\Curl;
use Buzz\Message\Form\FormRequest;
use Buzz\Message\Form\FormUpload;
use Buzz\Message\Response;

class UploadPieApi
{
    const REQUEST_URL = 'http://uploadpie.com/';

    /**
     * @var Curl
     */
    private $client;

    /**
     * @param Curl|null $client
     */
    public function __construct(Curl $client = null)
    {
        $this->client = $client ?: new Curl();
    }

    /**
     * @param  string $binaryImage
     * @param  string $filename
     * @param  int    $expire
     *
     * @return Response
     */
    public function call($binaryImage, $filename, $expire)
    {
        $response = new Response();

        $image = new FormUpload();
        $image->setFilename($filename);
        $image->setContent($binaryImage);

        $request = $this->buildRequest($image, $expire);
        $this->client->setOption(CURLOPT_TIMEOUT, 10000);
        $this->client->send($request, $response);

        return $this->processResponse($response);
    }

    /**
     * @param  Response $response
     *
     * @return string
     */
    private function processResponse(Response $response)
    {
        $matches = [];

        preg_match('/<input.*value="(.*)"/U', $response->getContent(), $matches);

        if (!isset($matches[1])) {
            throw new \RuntimeException('Screenshot upload failed');
        }

        return $matches[1];
    }

    /**
     * @param  FormUpload $image
     * @param  int        $expire
     *
     * @return FormRequest
     */
    private function buildRequest($image, $expire)
    {
        $request = new FormRequest();
        
        $request->fromUrl(self::REQUEST_URL);
        $request->setField('uploadedfile', $image);
        $request->setField('expire', $expire);
        $request->setField('upload', 1);

        return $request;
    }
}