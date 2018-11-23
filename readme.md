## Marinetraffic vessel position parser

### Using

```php
use Virp\Marinetraffic\Parser;

$parser = new Parser();

$position = $parser->position($url);

$latitude = $position.latitude;
$longitude = $position.longitude;
```

[Sample vessel url] (https://www.marinetraffic.com/en/ais/details/ships/shipid:421180/mmsi:538006224/imo:9418614/vessel:FRONT_BRAGE)