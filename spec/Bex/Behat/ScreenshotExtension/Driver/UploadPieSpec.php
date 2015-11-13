<?php

namespace spec\Bex\Behat\ScreenshotExtension\Driver;

use Bex\Behat\ScreenshotExtension\Driver\Service\UploadPieApi;
use PhpSpec\ObjectBehavior;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class UploadPieSpec extends ObjectBehavior
{
    function let(UploadPieApi $api)
    {
        $this->beConstructedWith($api);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Bex\Behat\ScreenshotExtension\Driver\UploadPie');
    }

    function it_should_call_the_api_with_the_correct_data(ContainerBuilder $container, UploadPieApi $api)
    {
        $api->call('imgdata', 'img_file_name.png', 1)->shouldBeCalled()->willReturn('imgurl');
        $this->load($container, ['expire' => 30]);
        $this->upload('imgdata', 'img_file_name.png')->shouldReturn('imgurl');
    }
}