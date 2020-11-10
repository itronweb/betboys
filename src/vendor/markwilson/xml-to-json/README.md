# XML to JSON converter

Basic XML to JSON conversion.

Usage:-
```` php
<?php

use MarkWilson\XmlToJson\XmlToJsonConverter;

$xml = new \SimpleXMLElement('<Element>Value</Element>');

$converter = new XmlToJsonConverter();
$json = $converter->convert($xml);
````
