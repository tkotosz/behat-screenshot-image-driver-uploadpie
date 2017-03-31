ImageDriver-UploadPie for Behat-ScreenshotExtension
=========================
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/tkotosz/behat-screenshot-image-driver-uploadpie/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/tkotosz/behat-screenshot-image-driver-uploadpie/?branch=master)
[![Build Status](https://scrutinizer-ci.com/g/tkotosz/behat-screenshot-image-driver-uploadpie/badges/build.png?b=master)](https://scrutinizer-ci.com/g/tkotosz/behat-screenshot-image-driver-uploadpie/build-status/master)
[![Build Status](https://travis-ci.org/tkotosz/behat-screenshot-image-driver-uploadpie.svg?branch=master)](https://travis-ci.org/tkotosz/behat-screenshot-image-driver-uploadpie)

This package is an image driver for the [bex/behat-screenshot](https://github.com/elvetemedve/behat-screenshot) behat extension which can upload the screenshot to [UploadPie](http://uploadpie.com) and print the url of the uploaded image.

Installation
------------

Install by adding to your `composer.json`:

```bash
composer require --dev bex/behat-screenshot-image-driver-uploadpie
```

Configuration
-------------

Enable the image driver in the Behat-ScreenshotExtension's config in `behat.yml` like this:

```yml
default:
  extensions:
    Bex\Behat\ScreenshotExtension:
      active_image_drivers: upload_pie
```

You must configure the authentication like this (you can register [here](https://uploadpie.com/why)):

```yml
default:
  extensions:
    Bex\Behat\ScreenshotExtension:
      active_image_drivers: upload_pie
      image_drivers:
        upload_pie:
          auth: 'API_KEY' # can be your authentication key or and environment variable name
```

You can configure the expire time of the uploaded image (by default it is 30m) like this:

```yml
default:
  extensions:
    Bex\Behat\ScreenshotExtension:
      active_image_drivers: upload_pie
      image_drivers:
        upload_pie:
          expire: '1h' # possible values: '30m', '1h', '6h', '1d', '1w'
```

Usage
-----

When you run behat and a step fails then the Behat-ScreenshotExtension will automatically take the screenshot and will pass it to the image driver, which will upload it and returns the URL of the uploaded image. So you will see something like this:

```bash
  Scenario:                           # features/feature.feature:2
    Given I have a step               # FeatureContext::passingStep()
    When I have a failing step        # FeatureContext::failingStep()
      Error (Exception)
Screenshot has been taken. Open image at http://uploadpie.com/idoftheimage
    Then I should have a skipped step # FeatureContext::skippedStep()
```
