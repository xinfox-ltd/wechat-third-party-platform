<?php

/**
 * [XinFox System] Copyright (c) 2011 - 2021 XINFOX.CN
 */
declare(strict_types=1);

namespace XinFox\WechatPlatform\MiniProgram;

use XinFox\WechatPlatform\AbstractApi;
use XinFox\WechatPlatform\HttpClient;

class QrCode extends AbstractApi
{
    /**
     * 获取小程序码
     * 调用本 API 可以获取小程序码，适用于需要的码数量极多的业务场景。通过该接口生成的小程序码，永久有效，数量暂无限制
     * @param string $authorizerAppId
     * @param string $scene 最大32个可见字符，只支持数字，大小写英文以及部分特殊字符：!#$&'()*+,/:;=?@-._~，其它字符请自行编码为合法字符（因不支持%，中文无法使用 urlencode 处理，请使用其他编码方式）
     * @param string $page 必须是已经发布的小程序存在的页面（否则报错），例如 pages/index/index, 根路径前不要填加 /,不能携带参数（参数请放在scene字段里），如果不填写这个字段，默认跳主页面
     * @param int $width 二维码的宽度，单位 px，最小 280px，最大 1280px
     * @param bool $autoColor 自动配置线条颜色，如果颜色依然是黑色，则说明不建议配置主色调，默认 false
     * @param array $lineColor auto_color 为 false 时生效，使用 rgb 设置颜色 例如 {"r":"xxx","g":"xxx","b":"xxx"} 十进制表示
     * @param bool $isGyaLine 是否需要透明底色，为 true 时，生成透明底色的小程序
     * @return string
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @throws \XinFox\WechatPlatform\Exception\ApiException
     * @throws \XinFox\WechatPlatform\Exception\AuthorizationNotExistException
     * @throws \XinFox\WechatPlatform\Exception\ComponentVerifyTicketException
     */
    public function getWxACodeUnLimit(
        string $authorizerAppId,
        string $scene,
        string $page = '',
        int $width = 430,
        bool $autoColor = true,
        array $lineColor = [],
        bool $isGyaLine = false
    ): string {
        $token = $this->platform->getAuthorizerAccessToken($authorizerAppId);
        $api = "https://api.weixin.qq.com/wxa/getwxacodeunlimit?access_token={$token}";

        $data = [
            'scene' => $scene,
            'width' => $width,
            'is_hyaline' => $isGyaLine
        ];

        if ($page) {
            $data['page'] = $page;
        }

        if ($autoColor === false && $lineColor) {
            $data['auto_color'] = true;
            $data['line_color'] = json_encode($lineColor);
        }

        return HttpClient::getInstance()
            ->post($api, $data);
    }
}