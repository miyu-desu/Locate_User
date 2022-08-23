<?php
header('Content-Type: application/javascript');
?>
navigator.geolocation.getCurrentPosition(function(pos) { // GPS데이터 로드
    var lat = pos.coords.latitude; // 위도
    var lon = pos.coords.longitude; // 경도

    $.ajax({ // ajax를 통해 서버에 요청
            type: "POST", // POST로 요청
            url: "renyu.php", // renyu.php로 요청
            headers: { "Authorize_system": "Renyu106" }, // 헤더에 Authorize_system추가
            data: '{"lat":' + lat + ',"lon":' + lon + '}', // 위도와 경도를 넘겨줌
            async:false, // 전역함수로 사용하기 위해 false
            cache: false, // 캐시 사용 안함
            success: function(json) {     
                
            var address_name = json.address_name;
            var region_1depth_name = json.region_1depth_name;
            var region_2depth_name = json.region_2depth_name;
            var region_3depth_name = json.region_3depth_name;
            var data1 = json.data1;
            var data2 = json.data2;
            var data3 = json.data3;
            var data4 = json.data4;
            var data5 = json.data5;
            var data6 = json.data6;
            var data7 = json.data7;
            var data8 = json.data8;
            var data9 = json.data9;
            var data10 = json.data10;
            var data11 = json.data11;
            var data12 = json.data12;
            var data13 = json.data13;
            var data14 = json.data14;
            var data15 = json.data15;


            document.getElementById('address_name').innerHTML=address_name;
            document.getElementById('data1').innerHTML=data1;
            document.getElementById('data2').innerHTML=data2;
            document.getElementById('data3').innerHTML=data3;
            document.getElementById('data4').innerHTML=data4;
            document.getElementById('data5').innerHTML=data5;
            document.getElementById('data6').innerHTML=data6;
            document.getElementById('data7').innerHTML=data7;
            document.getElementById('data8').innerHTML=data8;
            document.getElementById('data9').innerHTML=data9;
            document.getElementById('data10').innerHTML=data10;
            document.getElementById('data11').innerHTML=data11;
            document.getElementById('data12').innerHTML=data12;
            document.getElementById('data13').innerHTML=data13;
            document.getElementById('data14').innerHTML=data14;
            document.getElementById('data15').innerHTML=data15;


            }
        });
});