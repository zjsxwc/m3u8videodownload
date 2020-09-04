<?php

$downloadPath = "/home/zjsxwc/Downloads/ts";


$dataList = [
    [
        "title" => "【英语】非谓语做主、宾、表语",
        "m3u8Url" => "https://1252524126.vod2.myqcloud.com/9764a7a5vodtransgzp1252524126/47f2b4895285890803027343638/drm/v.f230.m3u8",
    ],
    [
        "title" => "【英语】非谓语做定语",
        "m3u8Url" => "https://1252524126.vod2.myqcloud.com/9764a7a5vodtransgzp1252524126/5a8212aa5285890803065168038/drm/v.f230.m3u8",
    ],
    [
        "title" => "【英语】非谓语做状语",
        "m3u8Url" => "https://1252524126.vod2.myqcloud.com/9764a7a5vodtransgzp1252524126/f5c823a35285890803104015856/drm/v.f230.m3u8",
    ],
    [
        "title" => "【英语】非谓语综合练习",
        "m3u8Url" => "https://1252524126.vod2.myqcloud.com/9764a7a5vodtransgzp1252524126/d2f3c1635285890803141870243/drm/v.f230.m3u8",
    ],
    [
        "title" => "【英语】2009年 text 1 短语搭配",
        "m3u8Url" => "https://1252524126.vod2.myqcloud.com/9764a7a5vodtransgzp1252524126/2d17b0555285890803553922778/drm/v.f230.m3u8",
    ],
    [
        "title" => "【英语】2009年 text 2 短语搭配",
        "m3u8Url" => "https://1252524126.vod2.myqcloud.com/9764a7a5vodtransgzp1252524126/3f5e3d345285890803591706829/drm/v.f230.m3u8",
    ],
    [
        "title" => "【英语】2009年 text 3 短语搭配",
        "m3u8Url" => "https://1252524126.vod2.myqcloud.com/9764a7a5vodtransgzp1252524126/95ff8e405285890803612381240/drm/v.f230.m3u8",
    ],
    [
        "title" => "【英语】2009年 text 4 短语搭配",
        "m3u8Url" => "https://1252524126.vod2.myqcloud.com/9764a7a5vodtransgzp1252524126/83b2295e5285890803649999317/drm/v.f230.m3u8",
    ],

];

function downloadM3u8($title, $m3u8Url, $downloadPath) {
    if (strpos($m3u8Url, "/drm/") === false) {
        echo $title." 不能被下载 地址缺少 /drm/ \n";
        return;
    }
    $ss = explode("drm", $m3u8Url);
    $tsUrlPrefix = $ss[0] . "drm/";//$tsUrlPrefix = "https://1252524126.vod2.myqcloud.com/9764a7a5vodtransgzp1252524126/4c8833815285890799178983109/drm/";
    $title = str_replace(":", "", $title);
    $title = str_replace(" ", "", $title);
    $title = str_replace(".", "", $title);
    $title = str_replace("?", "", $title);

    $m3u8 = file_get_contents($m3u8Url);
    $m3u8Lines = explode("\n", $m3u8);

    $files = "";
    foreach ($m3u8Lines as $m3u8Line) {
        if (strpos($m3u8Line, "f230.ts?") !== false) {
            $url = $tsUrlPrefix . $m3u8Line;
            $s1s = explode("&", $m3u8Line);
            if (isset($s1s[0])) {
                $s2s = explode("=", $s1s[0]);
                if (isset($s2s[1]) && is_numeric($s2s[1])) {
                    $n1 = str_replace("?", "_", $m3u8Line);
                    $n1 = str_replace("=", "_", $n1);
                    $n1 = str_replace("&", "_", $n1);
                    $n1 = $n1 . ".ts";

                    echo "fetch " . $n1  . PHP_EOL;
                    $files .= $n1 . PHP_EOL;
                    $ts = file_get_contents($url);
                    file_put_contents($downloadPath . "/" . $n1, $ts);
                }
            }
        } else {
            $files .= $m3u8Line . PHP_EOL;
        }
    }

    file_put_contents($downloadPath . "/files.m3u8", $files);

//ffmpeg -allowed_extensions ALL -protocol_whitelist "https,file,http,crypto,tcp,tls"   -i  files.m3u8 -c copy out.mp4
    $cmd = sprintf("ffmpeg -allowed_extensions ALL -protocol_whitelist \"https,file,http,crypto,tcp,tls\" -i  %sfiles.m3u8 -c copy %s.mp4",
        $downloadPath."/", $downloadPath."/".$title);

    $output = shell_exec($cmd);
    var_dump($title.$output);
    shell_exec("rm {$downloadPath}/*.ts");
    shell_exec("rm {$downloadPath}/*.m3u8");
}

foreach ($dataList as $data) {
    downloadM3u8($data["title"], $data["m3u8Url"], $downloadPath);
}
