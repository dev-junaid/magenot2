<?php
namespace MTalkz\Sms\Observer;

use Magento\Framework\Event\ObserverInterface;

class OrderSavedObserver implements ObserverInterface
{
    public $apiHelper;
    public function __construct(\MTalkz\Sms\Helper\ApiHelper $apiHelper)
    {
        $this->apiHelper = $apiHelper;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        try {
            $order = $observer->getEvent()->getOrder();
            if (!$order || !($order instanceof \Magento\Framework\Model\AbstractModel)) return;

            $order_id = $order->getId();
            $order_state = $order->getState();

            $this->apiHelper->log("Called for order ID $order_id having state $order_state");

            //Admin notification
            $showNotification = 1 == $this->apiHelper->getApiStoreConfig('admin_sms/enable');
            if ($showNotification && 'new' == $order_state && 1 != $this->apiHelper->getSmsSentStatus($order_id)) {
                $this->apiHelper->log('Sending admin notification for new order');
                $template = $this->apiHelper->getApiStoreConfig('admin_sms/template');
                $message = $this->apiHelper->getFormattedMessage($order, $template);
                $to = $this->apiHelper->getApiStoreConfig('account_settings/admin_phone');
                $country = $this->apiHelper->getStoreConfig('general/country/default');
                $this->apiHelper->sendSMS($to, $message, $country, $order_id, 'admin_sms');
                $this->apiHelper->setSmsSentStatus($order_id);
            }

            //Customer notification
            if ('new' == $order_state && 'pending' != $order->getStatus()) return;
            $config_prefix = in_array($order_state, ['new', 'pending', 'processing', 'complete', 'closed', 'canceled', 'holded']) ? "{$order_state}_order" : 'custom_status';
            $showNotification = 1 == $this->apiHelper->getApiStoreConfig("$config_prefix/enable");
            if ($showNotification) {
                $this->apiHelper->log('Sending customer notification for order state ' . $order_state);
                $template = $this->apiHelper->getApiStoreConfig("$config_prefix/template");
                $message = $this->apiHelper->getFormattedMessage($order, $template);
                $address = $order->getShippingAddress() ?: $order->getBillingAddress();
                $country = $address->getCountryId();
                $to = $address->getTelephone();
                $this->apiHelper->sendSMS($to, $message, $country, $order_id, $config_prefix);
            }
        } catch (\Magento\Framework\Exception\LocalizedException $e) {
            $this->apiHelper->log('Exception during order['.$order->getId().'] save:'.$e->getMessage());
        }
    }
}
