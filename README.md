# silverstripe-simpleinstagram
Retrieves the Instagram feed for a given user for embedding in a SilverStripe template

This module is a simple wrapper for the [Instagram user feed PHP library](https://github.com/pgrimaud/instagram-user-feed)


# Requirements
* Silverstripe 4.x
* pgrimaud/instagram-user-feed 5.x

# Installation
* Install the code with `composer require dorsetdigital/silverstripe-simpleinstagram`
* Run a `dev/build?flush` to update your project

# Usage

To use the module, you will need to add a couple of bits of code to your template and its associated controller.
For example, if you were adding the feed to your HomePage class:

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

Note: It is strongly recommended that you wrap the Instagram feed data in a SilverStripe cache block to help reduce the dependency on the external service.

