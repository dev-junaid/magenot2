<?php
namespace MTalkz\Sms\Helper;

use Magento\Framework\App\Helper\Context;
use Magento\Framework\App\Filesystem\DirectoryList;
use Monolog\Handler\StreamHandler;

class ApiHelper extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * Logging instance
     * @var \MTalkz\Sms\Logger\Logger
     */
    public $logger;
    
    /**
     * Price Helper
     * @var \Magento\Framework\Pricing\Helper\Data
     */
    public $priceHelper;

    public const COUNTRY_CODES = array(
        'AC' => '247',
        'AD' => '376',
        'AE' => '971',
        'AF' => '93',
        'AG' => '1268',
        'AI' => '1264',
        'AL' => '355',
        'AM' => '374',
        'AO' => '244',
        'AQ' => '672',
        'AR' => '54',
        'AS' => '1684',
        'AT' => '43',
        'AU' => '61',
        'AW' => '297',
        'AX' => '358',
        'AZ' => '994',
        'BA' => '387',
        'BB' => '1246',
        'BD' => '880',
        'BE' => '32',
        'BF' => '226',
        'BG' => '359',
        'BH' => '973',
        'BI' => '257',
        'BJ' => '229',
        'BL' => '590',
        'BM' => '1441',
        'BN' => '673',
        'BO' => '591',
        'BQ' => '599',
        'BR' => '55',
        'BS' => '1242',
        'BT' => '975',
        'BW' => '267',
        'BY' => '375',
        'BZ' => '501',
        'CA' => '1',
        'CC' => '61',
        'CD' => '243',
        'CF' => '236',
        'CG' => '242',
        'CH' => '41',
        'CI' => '225',
        'CK' => '682',
        'CL' => '56',
        'CM' => '237',
        'CN' => '86',
        'CO' => '57',
        'CR' => '506',
        'CU' => '53',
        'CV' => '238',
        'CW' => '599',
        'CX' => '61',
        'CY' => '357',
        'CZ' => '420',
        'DE' => '49',
        'DJ' => '253',
        'DK' => '45',
        'DM' => '1767',
        'DO' => '1809',
        'DO' => '1829',
        'DO' => '1849',
        'DZ' => '213',
        'EC' => '593',
        'EE' => '372',
        'EG' => '20',
        'EH' => '212',
        'ER' => '291',
        'ES' => '34',
        'ET' => '251',
        'EU' => '388',
        'FI' => '358',
        'FJ' => '679',
        'FK' => '500',
        'FM' => '691',
        'FO' => '298',
        'FR' => '33',
        'GA' => '241',
        'GB' => '44',
        'GD' => '1473',
        'GE' => '995',
        'GF' => '594',
        'GG' => '44',
        'GH' => '233',
        'GI' => '350',
        'GL' => '299',
        'GM' => '220',
        'GN' => '224',
        'GP' => '590',
        'GQ' => '240',
        'GR' => '30',
        'GT' => '502',
        'GU' => '1671',
        'GW' => '245',
        'GY' => '592',
        'HK' => '852',
        'HN' => '504',
        'HR' => '385',
        'HT' => '509',
        'HU' => '36',
        'ID' => '62',
        'IE' => '353',
        'IL' => '972',
        'IM' => '44',
        'IN' => '91',
        'IO' => '246',
        'IQ' => '964',
        'IR' => '98',
        'IS' => '354',
        'IT' => '39',
        'JE' => '44',
        'JM' => '1876',
        'JO' => '962',
        'JP' => '81',
        'KE' => '254',
        'KG' => '996',
        'KH' => '855',
        'KI' => '686',
        'KM' => '269',
        'KN' => '1869',
        'KP' => '850',
        'KR' => '82',
        'KW' => '965',
        'KY' => '1345',
        'KZ' => '7',
        'LA' => '856',
        'LB' => '961',
        'LC' => '1758',
        'LI' => '423',
        'LK' => '94',
        'LR' => '231',
        'LS' => '266',
        'LT' => '370',
        'LU' => '352',
        'LV' => '371',
        'LY' => '218',
        'MA' => '212',
        'MC' => '377',
        'MD' => '373',
        'ME' => '382',
        'MF' => '590',
        'MG' => '261',
        'MH' => '692',
        'MK' => '389',
        'ML' => '223',
        'MM' => '95',
        'MN' => '976',
        'MO' => '853',
        'MP' => '1670',
        'MQ' => '596',
        'MR' => '222',
        'MS' => '1664',
        'MT' => '356',
        'MU' => '230',
        'MV' => '960',
        'MW' => '265',
        'MX' => '52',
        'MY' => '60',
        'MZ' => '258',
        'NA' => '264',
        'NC' => '687',
        'NE' => '227',
        'NF' => '672',
        'NG' => '234',
        'NI' => '505',
        'NL' => '31',
        'NO' => '47',
        'NP' => '977',
        'NR' => '674',
        'NU' => '683',
        'NZ' => '64',
        'OM' => '968',
        'PA' => '507',
        'PE' => '51',
        'PF' => '689',
        'PG' => '675',
        'PH' => '63',
        'PK' => '92',
        'PL' => '48',
        'PM' => '508',
        'PR' => '1787',
        'PR' => '1939',
        'PS' => '970',
        'PT' => '351',
        'PW' => '680',
        'PY' => '595',
        'QA' => '974',
        'QN' => '374',
        'QS' => '252',
        'QY' => '90',
        'RE' => '262',
        'RO' => '40',
        'RS' => '381',
        'RU' => '7',
        'RW' => '250',
        'SA' => '966',
        'SB' => '677',
        'SC' => '248',
        'SD' => '249',
        'SE' => '46',
        'SG' => '65',
        'SH' => '290',
        'SI' => '386',
        'SJ' => '47',
        'SK' => '421',
        'SL' => '232',
        'SM' => '378',
        'SN' => '221',
        'SO' => '252',
        'SR' => '597',
        'SS' => '211',
        'ST' => '239',
        'SV' => '503',
        'SX' => '1721',
        'SY' => '963',
        'SZ' => '268',
        'TA' => '290',
        'TC' => '1649',
        'TD' => '235',
        'TG' => '228',
        'TH' => '66',
        'TJ' => '992',
        'TK' => '690',
        'TL' => '670',
        'TM' => '993',
        'TN' => '216',
        'TO' => '676',
        'TR' => '90',
        'TT' => '1868',
        'TV' => '688',
        'TW' => '886',
        'TZ' => '255',
        'UA' => '380',
        'UG' => '256',
        'UK' => '44',
        'US' => '1',
        'UY' => '598',
        'UZ' => '998',
        'VA' => '379',
        'VA' => '39',
        'VC' => '1784',
        'VE' => '58',
        'VG' => '1284',
        'VI' => '1340',
        'VN' => '84',
        'VU' => '678',
        'WF' => '681',
        'WS' => '685',
        'XC' => '991',
        'XD' => '888',
        'XG' => '881',
        'XL' => '883',
        'XN' => '857',
        'XN' => '858',
        'XN' => '870',
        'XP' => '878',
        'XR' => '979',
        'XS' => '808',
        'XT' => '800',
        'XV' => '882',
        'YE' => '967',
        'YT' => '262',
        'ZA' => '27',
        'ZM' => '260',
        'ZW' => '263'
    );

    public function __construct(
        Context $context,
        \Magento\Framework\Pricing\Helper\Data $priceHelper,
        \MTalkz\Sms\Logger\Logger $logger
    ) {
        $this->logger = $logger;
        $this->priceHelper = $priceHelper;
        parent::__construct($context);
    }
    public function getStoreConfig($path)
    {
        return $this->scopeConfig->getValue(
            $path,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }
    public function getApiStoreConfig($path)
    {
        return $this->getStoreConfig('mtalkz_sms/' . $path);
    }
    public function getSmsSentStatus($order_id)
    {
        $objManager = \Magento\Framework\App\ObjectManager::getInstance();
        $resource = $objManager->get('Magento\Framework\App\ResourceConnection');
        $connection= $resource->getConnection();
        $table = $resource->getTableName('sales_order');
        $query = "select sms_sent_status from {$table} where entity_id = ".(int)($order_id);
        return (int)($connection->fetchOne($query));
    }
    public function setSmsSentStatus($order_id)
    {
        $objManager = \Magento\Framework\App\ObjectManager::getInstance();
        $resource = $objManager->get('Magento\Framework\App\ResourceConnection');
        $connection= $resource->getConnection();
        $table = $resource->getTableName('sales_order');
        $query = "update {$table} set sms_sent_status=1 where entity_id = ".(int)($order_id);
        $connection->query($query);
    }    
    public function getVariables()
    {
        $variables = ['{ORDER-NUMBER}', '{ORDER-TOTAL}', '{ORDER-STATUS}', '{CARRIER-NAME}', '{PAYMENT-NAME}', '{CUSTOMER-FIRST-NAME}', '{CUSTOMER-LAST-NAME}', '{CUSTOMER-NAME}', '{CUSTOMER-EMAIL}'];
        return $variables;
    }
    public function getFormattedMessage($order, $template)
    {
        $variables = $this->getVariables();
        $values =  [ $order->getOriginalIncrementId() ?: $order->getIncrementId(), strip_tags($this->priceHelper->currency($order->getGrandTotal())), $order->getStatus(), $order->getShippingDescription(), $order->getPayment()->getMethodInstance()->getTitle(), $order->getCustomerFirstname(), $order->getCustomerLastname(), $order->getCustomerFirstname().' '.$order->getCustomerLastname(), $order->getCustomerEmail() ];
        return urlencode(str_replace($variables, $values, $template));
    }
    public function getISDCode($country)
    {
        return self::COUNTRY_CODES[$country] ?? '';
    }
    public function sendMessage($message, $phone, $country='') {
        $phone = ltrim(preg_replace('/[^0-9]/', '', $phone), '0');

        $use_api2 = $this->getApiStoreConfig('account_settings/use_api2');
        $apikey = $this->getApiStoreConfig('account_settings/apikey');
        $sender = $this->getApiStoreConfig('account_settings/sender');
        
        if ($use_api2) {
            $code = $country ? $this->getISDCode($country) : '';
            if ( !empty($code) && 0 !== strpos($phone, $code)) {
                $phone = $code . $phone;
            }
            $url = "https://japi.instaalerts.zone/httpapi/QueryStringReceiver?ver=1.0&key=$apikey&encrpt=0&dest=$phone&send=$sender&text=$message&intl=1";
        } else {
            // $message = rawurlencode($message);
            $message = str_replace(' ', '%20', $message);
            $phone = "92$phone";
            $url = "http://api.bizsms.pk/api-send-branded-sms.aspx?username=$apikey&pass=$sender&text=$message&masking=PEL%20E-Shop&destinationnum=$phone&language=English";
        }

        return $this->fetchUrl($url);
    }
    public function sendSMS($to, $message, $country, $order_id, $action)
    {
        try {
            if ( empty($to) || empty($message) ) {
                $this->log("Cannot send '$message' to '$to'");
                return;
            }
            
            $response = $this->sendMessage($message, $to, $country);
            $this->log("{$order_id}-{$action}-Response");
            $this->log($response);
            
            $use_api2 = $this->getApiStoreConfig('account_settings/use_api2');
            $dlr_url = urlencode($this->getApiStoreConfig('account_settings/dlr_url') ?? '');
            if (!empty($dlr_url) && !$use_api2) {
                $decoded = json_decode($response, true);
                $msg_id = $decoded['msgid'] ?? '';
                if (empty($msg_id)) {
                    $this->log("Couldn't find message id to request DLR callback.");
                    return;
                }
                $apikey = $this->getApiStoreConfig('account_settings/apikey');
                $sender = $this->getApiStoreConfig('account_settings/sender');
                // $message = rawurlencode($message);
                $message = str_replace(' ', '%20', $message);
                $phone = "92$phone";
                $url = "http://api.bizsms.pk/api-send-branded-sms.aspx?username=$apikey&pass=$sender&text=$message&masking=PEL%20E-Shop&destinationnum=$phone&language=English";

                $this->log("{$order_id}-{$action}-DLR-Request: ".$url);
                $response = $this->fetchUrl($url);
                $this->log("{$order_id}-{$action}-DLR-Response");
                $this->log($response);
            }
        } catch (\Magento\Framework\Exception\LocalizedException $e) {
            $this->log("{$order_id}-{$action}-Response: ".$e->getMessage());
        }
    }
    public function fetchUrl($url)
    {
        $options = [CURLOPT_URL => $url, CURLOPT_RETURNTRANSFER => true, CURLOPT_HEADER => false, CURLOPT_SSL_VERIFYPEER => false, CURLOPT_POST => false, CURLOPT_FOLLOWLOCATION => true];

        $ch = curl_init();
        curl_setopt_array($ch, $options);
        $response = curl_exec($ch);
        if (!$response) {
            throw new \Magento\Framework\Exception\LocalizedException(curl_error($ch));
        }
        curl_close($ch);

        return $response;
    }
    public function log($content)
    {
        if ($this->getApiStoreConfig('account_settings/debug')) {
            $this->logger->debug($content);
        }
    }
}
