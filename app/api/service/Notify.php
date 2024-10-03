<?php
// +----------------------------------------------------------------------
// | 萤火商城系统 [ 致力于通过产品和服务，帮助商家高效化开拓市场 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017~2024 https://www.yiovo.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed 这不是一个自由软件，不允许对程序代码以任何形式任何目的的再发行
// +----------------------------------------------------------------------
// | Author: 萤火科技 <admin@yiovo.com>
// +----------------------------------------------------------------------
declare (strict_types=1);

namespace app\api\service;

use app\api\model\Payment as PaymentModel;
use app\api\model\PaymentTrade as PaymentTradeModel;
use app\api\model\PaymentTemplate as PaymentTemplateModel;
use app\api\service\order\PaySuccess as OrderPaySuccesService;
use app\api\service\recharge\PaySuccess as RechargePaySuccesService;
use app\common\enum\OrderType as OrderTypeEnum;
use app\common\enum\payment\Method as PaymentMethodEnum;
use app\common\library\Log;
use app\common\library\helper;
use app\common\library\payment\gateway\Driver;
use app\common\library\payment\Facade as PaymentFacade;
use app\common\library\payment\gateway\driver\wechat\V3 as WechatPaymentV3;
use cores\exception\BaseException;

/**
 * 服务类：第三方支付异步通知
 * Class Notify
 * @package app\api\service
 */
class Notify
{
    /**
     * 支付成功异步通知 (微信支付V2)
     * @return string
     */
    public function wechatV2(): string
    {
        try {
            // 获取第三方交易记录
            $tradeInfo = $this->getTradeInfo(PaymentMethodEnum::WECHAT, 'v2');
            // 构建支付模块
            $Payment = $this->getPayment($tradeInfo);
            // 验证异步通知参数是否合法
            if (!$Payment->notify()) {
                throwError($Payment->getError() ?: '异步通知验证未通过');
            }
            // 订单支付成功事件
            $this->orderPaySucces($tradeInfo, $Payment->getNotifyParams());
        } catch (\Throwable $e) {
            // 记录错误日志
            Log::append('Notify-wechat', ['errMessage' => $e->getMessage()]);
        }
        return isset($Payment) ? $Payment->getNotifyResponse() : 'FAIL';
    }

    /**
     * 支付成功异步通知 (微信支付V3)
     * @return string
     * @throws BaseException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function wechatV3(): string
    {
        try {
            // 通过微信支付v3平台证书序号 获取支付模板
            $wechatpaySerial = \request()->header('wechatpay-serial');
            $templateInfo = PaymentTemplateModel::findByWechatpaySerial($wechatpaySerial);
            empty($templateInfo) && throwError("未找到该平台证书序号：$wechatpaySerial");

            // 从支付模板中取出v3apikey 用于解密异步通知的密文
            $apiv3Key = $templateInfo['config']['wechat']['mchType'] === 'provider'
                ? $templateInfo['config']['wechat']['provider']['spApiKey']
                : $templateInfo['config']['wechat']['normal']['apiKey'];

            // 从支付模板中取出微信支付平台证书文件 用于验证API签名
            $fileName = $templateInfo['config']['wechat'][$templateInfo['config']['wechat']['mchType']]['platformCert'];
            $platformCertificateFilePath = PaymentTemplateModel::realPathCertFile(
                PaymentMethodEnum::WECHAT, $fileName, $templateInfo['store_id']
            );

            // 验证异步通知是否合法并获取第三方支付交易订单号
            $V3 = new WechatPaymentV3();
            $outTradeNo = $V3->notify($apiv3Key, $platformCertificateFilePath);
            empty($outTradeNo) && throwError('异步通知验证未通过');

            // 获取第三方交易记录
            $tradeInfo = PaymentTradeModel::detailByOutTradeNo($outTradeNo);
            // 订单支付成功事件
            $this->orderPaySucces($tradeInfo, $V3->getNotifyParams());
        } catch (\Throwable $e) {
            // 记录错误日志
            Log::append('Notify-wechat', ['errMessage' => $e->getMessage()]);
        }
        return '';
    }

    /**
     * 支付成功异步通知 (支付宝)
     * @return string
     */
    public function alipay(): string
    {
        try {
            // 获取第三方交易记录
            $tradeInfo = $this->getTradeInfo(PaymentMethodEnum::ALIPAY);
            // 构建支付模块
            $Payment = $this->getPayment($tradeInfo);
            // 验证异步通知参数是否合法
            if (!$Payment->notify()) {
                throwError($Payment->getError() ?: '异步通知验证未通过');
            }
            // 订单支付成功事件
            $this->orderPaySucces($tradeInfo, $Payment->getNotifyParams());
        } catch (\Throwable $e) {
            // 记录错误日志
            Log::append('Notify-alipay', ['errMessage' => $e->getMessage()]);
        }
        return isset($Payment) ? $Payment->getNotifyResponse() : 'FAIL';
    }

    /**
     * 订单支付成功事件
     * @param PaymentTradeModel $tradeInfo
     * @param array $paymentData 第三方支付异步回调的
     */
    private function orderPaySucces(PaymentTradeModel $tradeInfo, array $paymentData)
    {
        // 记录日志
        Log::append('Notify-orderPaySucces', [
            'orderType' => OrderTypeEnum::data()[$tradeInfo['order_type']]['name'],
            'tradeInfo' => $tradeInfo->toArray(),
        ]);
        try {
            // 订单支付成功业务处理 (商城订单)
            if ($tradeInfo['order_type'] == OrderTypeEnum::ORDER) {
                $service = new OrderPaySuccesService;
                $service->setOrderNo($tradeInfo['order_no'])
                    ->setMethod($tradeInfo['pay_method'])
                    ->setTradeId($tradeInfo['trade_id'])
                    ->setPaymentData($paymentData)
                    ->handle();
            }
            // 订单支付成功业务处理 (余额充值订单)
            if ($tradeInfo['order_type'] == OrderTypeEnum::RECHARGE) {
                $service = new RechargePaySuccesService;
                $service->setOrderNo($tradeInfo['order_no'])
                    ->setMethod($tradeInfo['pay_method'])
                    ->setTradeId($tradeInfo['trade_id'])
                    ->setPaymentData($paymentData)
                    ->handle();
            }
            Log::append('Notify-orderPaySucces', ['message' => '订单支付成功']);
        } catch (\Throwable $e) {
            // 记录错误日志
            Log::append('Notify-orderPaySucces', ['errMessage' => $e->getMessage()]);
        }
    }

    /**
     * 获取当前异步请求参数中的交易订单号
     * @param string $method 支付方式
     * @param string $wxapyVersion 微信支付版本号 v2或v3
     * @return string|null
     */
    private function getOutTradeNo(string $method, string $wxapyVersion = 'v2'): ?string
    {
        if ($method === PaymentMethodEnum::WECHAT) {
            if ($wxapyVersion === 'v2') {
                $xml = \file_get_contents('php://input');
                $data = helper::xmlToArray($xml);
                return $data['out_trade_no'];
            }
            if ($wxapyVersion === 'v3') {

            }
        }
        if ($method === PaymentMethodEnum::ALIPAY) {
            return \request()->post('out_trade_no');
        }
        return null;
    }

    /**
     * 获取第三方交易记录
     * @param string $method 支付方式
     * @param string $wxapyVersion 微信支付版本号 v2或v3
     * @return PaymentTradeModel|null
     * @throws BaseException
     */
    private function getTradeInfo(string $method, string $wxapyVersion = 'v2'): ?PaymentTradeModel
    {
        // 获取第三方交易记录
        $outTradeNo = $this->getOutTradeNo($method, $wxapyVersion);
        return PaymentTradeModel::detailByOutTradeNo($outTradeNo);
    }

    /**
     * 获取支付模块
     * @param PaymentTradeModel $tradeInfo 第三方交易记录
     * @return Driver|null
     * @throws BaseException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    private function getPayment(PaymentTradeModel $tradeInfo): ?Driver
    {
        // 获取支付方式的配置信息
        $options = $this->getPaymentConfig($tradeInfo['pay_method'], $tradeInfo['client'], $tradeInfo['store_id']);
        // 构建支付模块
        return PaymentFacade::store($tradeInfo['pay_method'])->setOptions($options, $tradeInfo['client']);
    }

    /**
     * 获取支付方式的配置信息
     * @param string $method 支付方式
     * @param string $client 下单客户端
     * @return mixed
     * @throws BaseException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    private function getPaymentConfig(string $method, string $client, int $storeId = null)
    {
        $PaymentModel = new PaymentModel;
        $templateInfo = $PaymentModel->getPaymentInfo($method, $client, $storeId);
        return $templateInfo['template']['config'][$method];
    }
}