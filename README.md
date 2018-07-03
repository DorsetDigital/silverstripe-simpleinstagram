# silverstripe-simpleinstagram
Retrieves the Instagram feed for a given user for embedding in a SilverStripe template

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/DorsetDigital/silverstripe-simpleinstagram/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/DorsetDigital/silverstripe-simpleinstagram/?branch=master)
[![Build Status](https://scrutinizer-ci.com/g/DorsetDigital/silverstripe-simpleinstagram/badges/build.png?b=master)](https://scrutinizer-ci.com/g/DorsetDigital/silverstripe-simpleinstagram/build-status/master)
[![License](https://img.shields.io/badge/License-BSD%203--Clause-blue.svg)](LICENSE.md)
[![Version](http://img.shields.io/packagist/v/dorsetdigital/silverstripe-simpleinstagram.svg?style=flat)](https://packagist.org/packages/dorsetdigital/silverstripe-simpleinstagram)


This module is a simple wrapper for the [Instagram user feed PHP library](https://github.com/pgrimaud/instagram-user-feed)


# Requirements
* Silverstripe 4.x
* pgrimaud/instagram-user-feed 5.x

# Installation
* Install the code with `composer require dorsetdigital/silverstripe-simpleinstagram`
* Run a `dev/build?flush` to update your project

# Usage

To use the module, you will need to add a couple of bits of code to your template and its associated controller.  The Instagram username is passed in as the (required) argument when instantiating the class.
For example, if you were adding a feed to your HomePage class for the 'natgeo' Instagram account:

```php
<?php

use DorsetDigital\SimpleInstagram\InstagramHelper;

class HomePage extends Page
{

 public function getInstagramFeed()
 {
  $insta = InstagramHelper::create('natgeo');
  return $insta->getFeed();  
 }
}
```

and then in your HomePage.ss file you would use the following syntax to display the data:

```php
 <% with $InstagramFeed %>   
 <h2 class="instagram__username">$FullName ($UserName)</h2>
 <a href="$Link">
  <img src="$ProfileImage" alt="Profile Image"/>
 </a>
 <p class="instagram__biotext">$Bio</p>
 <p class="instagram__stats">Followers: $Followers  /  Following: $Following</p>
 <% loop $Items %>
  <div class="instagram__post-holder">
  <a href="$Link" target="_blank" title="Link to Instagram post">
   <img src="$ThumbURL" alt="$Caption" class="$Type instagram__postimage"/>
   <p class="instagram__caption">$Caption</p>
   <p class="instagram__likes">Liked: $Likes</p>
   <p class="instagram__date">Posted: $Date</p>
  </a>
 </div>
 <% end_loop %>
 <% end_with %>
```

The markup shown above is purely for example purposes.  You can format / style it any way you want to suit your project.


# Performance 
In order to help reduce the dependency on the external service and to improve efficiency, the module supports caching via the standard SilverStripe mechanisms.   Caching is disabled by default, but it can be enabled by adding a yml configuration to your project, eg:

```yaml
---
Name: instagramcache
---

DorsetDigital\SimpleInstagram\InstagramHelper:
  enable_cache: true
  cache_time: 900
```

Enable the cache by setting `enable_cache` to `true`.  Set the cache time (in seconds) with `cache_time`
