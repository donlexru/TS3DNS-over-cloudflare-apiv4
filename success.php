<!-- Конфигурация проверки -->
<?php

$service = '_ts3';

$protocol = '_udp';

function umlautepas($string)

{

    $upas = Array("ä" => "ae", "ü" => "ue", "ö" => "oe", "Ä" => "Ae", "Ü" => "Ue", "Ö" => "Oe");

    return strtr($string, $upas);

}



?>

<?php

//error_reporting(0);

include("config.php");

$domain = isset($_POST['select']) && in_array($_POST['select'], $domains) ? $_POST['select'] : '';
if (empty($domain)) die('<div class="alert alert-danger" role="alert"><strong>Ошибка!</strong> Invalid domain selected</div>');
global $domain;

$zoneId = getZoneId($domain);
$recList = getRecordsList($zoneId, false, $service.'.'.$protocol.'.'.$_POST['subname'].'.'.$domain);

if($recList === false)
{
    header('Location: index.php?p=error');
    exit;
}
foreach($recList as $record)
{
    if($record['data']['name'] == $_POST['subname'])
    {
        die('<div class="alert alert-danger" role="alert"><strong>Ошибка!</strong> Домен уже существует</div>');
        exit;
    }
}

// Create a SRV for Teamspeak 3

// handling of -d curl parameter is here.

$param2 = array(
    'type' => 'SRV',
    'data' => array(
        //'name' => '' . $domain . '',
        'ttl' => 120,
        'priority' => 0,
        'service' => $service,
        'name' => '' . strtolower(umlautepas($_POST['subname'])) . '',
        'proto' => $protocol,
        'weight' => 5,
        'port' => intval($_POST['port']),
        'target' => '' . $_POST['ip'] . ''
    )
);
$result2 = cloudFlareRequest('zones/'.$zoneId.'/dns_records', $param2, true);

// rec_id

load_recs();

function load_recs()
{
    global $apikey;
    global $email;
    global $domain;
    global $zoneId;
    $result = cloudFlareRequest('zones/'.$zoneId.'/dns_records');
    foreach ($result["result"] as $item) {
        if ($item["type"] == 'A' && $item["name"] == strtolower(umlautepas($_POST['subname']))) {
            if ($item["content"] != $_POST['ip']) {
                $param4 = array(
                    'id' => '' . $item["id"] . '',
                    'type' => 'A',
                    'name' => '' . strtolower(umlautepas($_POST['subname'])) . '',
                    'content' => '' . $_POST['ip'] . '',
                    'service_mode' => 1,
                    'ttl' => 1
                );
                $result4 = cloudFlareRequest('zones/' . $zoneId . '/dns_records', $param4, true);
            }
        }
    }
}

function getRecordsList($zoneId, $type = false, $name = false)
{
    if($type)
    {
        $qData['type'] = $type;
    }
    if($name)
    {
        $qData['name'] = $name;
    }
    $data = cloudFlareRequest('zones/'.$zoneId.'/dns_records', $qData);
    if(isset($data['result']))
    {
        return $data['result'];
    }
    return false;
}

function getZoneId($domain)
{
    $data = cloudFlareRequest('zones', array('name' => $domain));
    if(isset($data['result'][0]['id']))
    {
        return $data['result'][0]['id'];
    }
    return false;
}

function cloudFlareRequest($tail, $qData = array(), $isPost = false)
{
    global $apikey;
    global $email;
    global $domain;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt(
        $ch,
        CURLOPT_HTTPHEADER,
        array(
            'X-Auth-Email: '.$email,
            'X-Auth-Key: '.$apikey,
            'Content-Type: application/json'
        )
    );
    if (!$isPost) {
        $httpQuery = '';
        if (!empty($qData)) {
            $httpQuery = '?' . http_build_query($qData);
        }
        curl_setopt($ch, CURLOPT_URL, 'https://api.cloudflare.com/client/v4/'.$tail.$httpQuery);
    } else {
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($qData));
        curl_setopt($ch, CURLOPT_URL, 'https://api.cloudflare.com/client/v4/'.$tail);
    }
    $res = curl_exec($ch);
    curl_close($ch);
    if($res)
    {
        $data = json_decode($res, true);
        return $data;
    }
    return false;
}

$daten = "|" . strtolower(umlautepas($_POST['subname'])) . "|" . $domain . "|" . $_POST['port'] . "";
?>

<!-- //Конфигурация проверки -->
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
     <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="signin.css" rel="stylesheet">
  </head>

  <body>

    <div class="container">
<div class="alert alert-success" role="alert">
  <strong>Успешно!</strong> Адрес: <? echo strtolower(umlautepas($_POST['subname'])) . '.' . $domain . ''; ?> установлен!
</div>
    </div> <!-- /container -->


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="js/bootstrap.js"></script>
  </body>
