<?php
/**
 * version_data
 *
 * @author lixworth <lixworth@outlook.com>
 * @create 2021/6/11 23:08
 */
declare(strict_types=1);

class RunTime
{
    public static string $t;

    public static function start()
    {
        self::$t = microtime();
    }

    public static function end(): string
    {
        $t1 = microtime();
        list($m0, $s0) = explode(" ", self::$t);
        list($m1, $s1) = explode(" ", $t1);
        return sprintf("%.3f ms", ($s1 + $m1 - $s0 - $m0) * 1000);
    }
}

Runtime::start();

$dom = new DOMDocument();

$data = @$dom->loadHTML(file_get_contents("https://www.118pan.com/o11286"));

//$xml = simplexml_import_dom($dom);

$xpath = new DOMXPath($dom);
//设置爬取规则

$page = $xpath->query('//*[@id="table_files_wrapper"]/div[2]/div[2]/div/ul/li[12]/a/@href');

$pages = substr($page->item(0)->textContent,-2);

$result_data = [];

for ($n=1;$n<$pages;$n++){

    $data = @$dom->loadHTML(file_get_contents("https://www.118pan.com/o11286&pg=".$n));

    $xpath = new DOMXPath($dom);
    $num = $xpath->query('//*[@id="table_files"]/tbody/tr');

    for ($i=0;$i<$num->length;$i++){
        $result = $xpath->query('//*[@id="table_files"]/tbody/tr['.($i+1).']/td[1]/div/a');
        $href = $xpath->query('//*[@id="table_files"]/tbody/tr['.($i+1).']/td[1]/div/a/@href');
        $file_name = substr($result->item(0)->textContent,0,strlen($result->item(0)->textContent)-4);
        if(substr($file_name,-4) === "Beta"){
            $beta = true;
            $version = substr($file_name,0,strlen($file_name)-4);
        }else{
            $beta = false;
            $version = $file_name;
        }
        $marjor = explode('.',$version);
        if((int)$marjor[0] == 0 || (int)$marjor[1] < 2){
            $pe = true;
        }else{
            $pe = false;
        }
        $result_data[] = [
            "marjor" => $marjor[0].".".$marjor[1], // 主要版本
            "beta" => (bool)$beta, // 是否为测试版本
            "version" => $version,
            "download" => "https://www.118pan.com/".$href->item(0)->textContent,
            "pe" => $pe, // 是否为MCPE,
            "protocol" => null, // 协议版本
        ];

    }
//    sleep(1);


}
// 我不是太会xpath
if(isset($argv[1])){
    if($argv[1] == "build"){
        $json = fopen(__DIR__ . "/public/data.json", "w");
        fwrite($json,json_encode($result_data));
        echo "Finish (".Runtime::end().")";
    }
}else{
    echo json_encode($result_data);
}

