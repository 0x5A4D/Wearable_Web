<?php
//City IDデータ読み込み
$city_data = unserialize(file_get_contents('./city_data.dat'));
$lwws_url = 'http://weather.livedoor.com/forecast/webservice/json/v1';

if (isset($_POST['city'])&& $_POST['city']!=""){
    $POSTDATA = $_POST['city'];
    //正しいデータかチェック
    echo $POSTDATA;
    if(preg_match("/^[0-9]+$/", $POSTDATA)){
        $lwws_url .= '?city='.sprintf("%06d", $POSTDATA);
        $lwws_data = file_get_contents($lwws_url);
        $lwws_ary = json_decode($lwws_data, true);
        
    }
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>LWWSリクエスト</title>
    </head>
    <script type="text/javascript">
    <?php
    //都道府県=>都市名=>cityidを格納したJavaScriptの配列を出力
    echo 'var CityAry = {';
    foreach ($city_data as $pref=>$cityary){
        echo "\"$pref\":{";
        foreach ($cityary as $city => $id) {
            echo "\"$city\":\"$id\",";
        }
        echo '},'."\n";
    }
    echo '};';
    ?>
    
    function CntHashLength(ary){
        var cnt = 0;
        for(var key in ary){
            cnt++;
        }
        return(cnt);
    }
    
    function Select(Obj){
        var sel_index = Obj.selectedIndex;
        var sel_pref = Obj[sel_index].text;
        var CitySelect = document.CityForm.city
        CitySelect.length = CntHashLength(CityAry[sel_pref]);
        var i = 0;
        for (var city in CityAry[sel_pref]){
                CitySelect.options[i].text = city;
                CitySelect.options[i].value = CityAry[sel_pref][city];
                i++;
        }
    }
    </script>
    <body>
        <h1>LWWSリクエスト</h1>
        
        <form action="request_lwws.php" name="CityForm" method="post">
            <select name="pref" onchange="Select(this)">
                <option value="" selected></option>
                <?php
                $pref = array_keys($city_data);
                foreach ($pref as $val){
                    echo '<option value="'.$val.'">'.$val.'</option>'."\n";
                }
                ?>
            </select>
            <select name="city">
                <option value=""></option>
            </select>
            <input type="submit" value="送信">
        </form>
        <?php if(isset($POSTDATA)){ ?>
        <h2>レスポンスデータ</h2>
        <pre>
        <?php
            print_r($lwws_ary);
        } ?>
        </pre>
    </body>
</html>
