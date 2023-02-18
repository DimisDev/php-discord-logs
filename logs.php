<?php
// Configurable Options
$discord_webhook = "https://discord.com/api/webhooks";
$webhook_name = 'Dimis Website Logs';
$webhook_pfp = 'https://cdn.discordapp.com/avatars/1069953502069149696/f5e4247f074ee055e37243e222feae55.png';
$thumbnail_image = 'https://cdn.discordapp.com/avatars/1069953502069149696/f5e4247f074ee055e37243e222feae55.png';


$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
$domain = $_SERVER['HTTP_HOST'];
$uri = $_SERVER['REQUEST_URI'];
$ip_api = "http://ip-api.com/json/$ip";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $ip_api);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);
$data = json_decode($response, true);

$country = $data['country'];
$city = $data['city'];
$isp = $data['isp'];
$org = $data['org'];
$coords_lat = $data['lat'];
$coords_lon = $data['lon'];
$asn = file_get_contents("https://ipapi.co/$ip/asn");
$ipver = file_get_contents("https://ipapi.co/$ip/version");
$continent = file_get_contents("https://ipapi.co/$ip/continent_code");
$countrycode = file_get_contents("https://ipapi.co/$ip/country_code_iso3");
$timezone = file_get_contents("https://ipapi.co/$ip/timezone");
$region = file_get_contents("https://ipapi.co/$ip/region");
$regioncode = file_get_contents("https://ipapi.co/$ip/region_code");
$ua = $_SERVER["HTTP_USER_AGENT"];

$general = "IP address: $ip\nIP Version: $ipver\nISP: $isp\nOrganisation: $org";
$location = "Country: $country\nCountry Code: $countrycode\nCity: $city\nRegion: $region ($regioncode)\nContinent: $continent\nTimezone: $timezone\nCoords: $coords_lat,$coords_lon";
$other = "ASN: $asn\nFull URI: $domain$uri\nUser Agent: $ua";

$embed = array(
    'title' => "Logs - $domain",
    'url' => "https://$domain",
    'color' => 0xd51961,
    'fields' => array(
        array(
            'name' => 'General IP Info',
            'value' => $general,
            'inline' => true,
        ),
        array(
            'name' => 'Location Info',
            'value' => $location,
            'inline' => true,
        ),
              array(
            'name' => 'Other Info',
            'value' => $other,
            'inline' => true,
        ),
    ),
      'thumbnail' => array(
        'url' => $thumbnail_image,
    ),
      'footer' => array(
        'text' => 'Made by Dimis with ❤️',
    ),
);

$payload = array(
    'embeds' => array($embed),
    'username' => $webhook_name,
    'avatar_url' => $webhook_pfp,
);
$json_payload = json_encode($payload);
$ch = curl_init($discord_webhook);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
curl_setopt($ch, CURLOPT_POSTFIELDS, $json_payload);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Content-Type: application/json',
    'Content-Length: ' . strlen($json_payload),
));
$result = curl_exec($ch);
curl_close($ch);
?>
