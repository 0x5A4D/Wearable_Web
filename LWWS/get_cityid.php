<?php
/*
http://weather.livedoor.com/weather_hacks/webservice

[1次細分区定義表]
http://weather.livedoor.com/forecast/rss/primary_area.xml
*/
$XmlFile = 'primary_area.xml';

$xml = simplexml_load_file($XmlFile);
$ldWeather = $xml->channel->children('ldWeather', TRUE);
$city_data = array();

foreach ($ldWeather->source->children()->pref as $preftag) {
    foreach ($preftag->city as $citytag){
        $area = $preftag['title'];
        $city = $citytag['title'];
        $city_data["$area"]["$city"] = (int)$citytag['id'];
      
        file_put_contents('./city_data.dat', serialize($city_data));
    }
}
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>1次細分区定義表</title>
    </head>
    <body>
        <h1>1次細分区定義表 - City ID</h1>
        <pre><?php print_r($city_data); ?></pre>
    </body>
</html>
