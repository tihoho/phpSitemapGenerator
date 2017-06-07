# phpSitemapGenerator
A simple class for creating sitemap via php

_Gratitude or rotten tomatoes send here: tiho1022@gmail.com_

## Examples of use

```php
<?php

use Silentlabs\SitemapGenerator as Sitemap;

$sitemap = new Sitemap('https://mydomain.com');
$sitemap->addLink('somelink1.html');
$sitemap->addLink('somelink2.php');
$sitemap->addLink('somelinkN');
$sitemap->build();

$savePath = '/var/www/mydomain.com/public/sitemap.xml';

if($sitemap->save($savePath)) {
	echo 'Success!';
} else {
	echo 'Fail!';
}
```

###### OR

```php
<?php

use Silentlabs\SitemapGenerator as Sitemap;

$sitemap = (new Sitemap())
	->setDomain('https://mydomain.com')
	->addLink('somelink1.html')
	->build()
	->save('path/to/save');

if($sitemap->isSaved) {
	echo 'Success!';
} else {
	echo 'Fail!';
}
```

###### OR

```php
<?php

use Silentlabs\SitemapGenerator as Sitemap;

$links = [
	'mylink',
	'mylink2.php',
	'mylink3.html',
	'mylink_N'
];

$sitemap = (new Sitemap())
	->setDomain('https://mydomain.com')
	->setDefaults([
		'changefreq' => 'weekly',
		'lastmod' => date('Y-m-d'),
	])
	->ignorePriority();
	
foreach($links as $link)
	$sitemap->addLink($link);
	
echo $sitemap->build()->get(); // Return a sitemap structure
```

###### OR something else, because it class a very flexible :)

Happy new code!
