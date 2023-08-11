<?php

// 目标网址
$targetUrl = "http://d5l0dvt14r5h8.eu.org/";

// 获取请求方法
$method = $_SERVER['REQUEST_METHOD'];

// 获取所有请求头
$headers = getallheaders();

// 获取请求体
$body = file_get_contents('php://input');

// 创建 cURL 句柄
$ch = curl_init();

// 设置 cURL 选项
curl_setopt($ch, CURLOPT_URL, $targetUrl);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// 添加缓存时间为30天
$cacheExpireTime = 60 * 60 * 24 * 30; // 30天的秒数
curl_setopt($ch, CURLOPT_HEADER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array("Cache-Control: max-age=$cacheExpireTime"));

// 执行 cURL 请求
$response = curl_exec($ch);

// 获取响应头信息
$responseHeaders = curl_getinfo($ch);

// 关闭 cURL 句柄
curl_close($ch);

// 设置响应头
foreach ($responseHeaders as $key => $value) {
    if (strpos($key, 'Content-Length') === false) {
        header("$key: $value");
    }
}

// 输出响应体
echo $response;
