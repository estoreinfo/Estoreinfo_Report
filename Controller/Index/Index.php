<?php

namespace Estoreinfo\Report\Controller\Index;

class Index extends \Estoreinfo\Report\Controller\Index\AbstractIndex
{
    public function execute()
    {
        $orders = [];
		
		$resource = $this->_objectManager->create('\Magento\Framework\App\ResourceConnection');
  		$readConnection = $resource->getConnection(\Magento\Framework\App\ResourceConnection::DEFAULT_CONNECTION);
		
		$order_table = $resource->getTableName('sales_order');
		$creditmemo_table = $resource->getTableName('sales_creditmemo');
		
		/*get all records*/
        $orders['all']['total'] = \Magento\Framework\App\ObjectManager::getInstance()->get('Magento\Sales\Model\OrderFactory')->create()->getCollection()->getSize();		
		$orders['all']['cancelled'] = \Magento\Framework\App\ObjectManager::getInstance()->get('Magento\Sales\Model\OrderFactory')->create()->getCollection()->addFieldToFilter('state', \Magento\Sales\Model\Order::STATE_CANCELED )->getSize();
		$orders['all']['refunded'] = \Magento\Framework\App\ObjectManager::getInstance()->get('Magento\Sales\Model\OrderFactory')->create()->getCollection()->addFieldToFilter('state', \Magento\Sales\Model\Order::STATE_CLOSED)->getSize();		
		
		$query = 'SELECT count(*) total_ontime FROM ' . $order_table . " WHERE HOUR(TIMEDIFF(created_at, updated_at)) < 48 AND state = '".\Magento\Sales\Model\Order::STATE_COMPLETE."' LIMIT 1";
		$orders['all']['ontime_shipment'] = $readConnection->fetchOne($query);
		
		$query = 'SELECT count(*) total_return FROM ' . $creditmemo_table . " WHERE 1 LIMIT 1";
		$orders['all']['returns'] = $readConnection->fetchOne($query);
		
		/*get 30 days records*/
		$fromDate = date('Y-m-d H:i:s', strtotime('-30 days'));
		$orders['30_days']['total'] = \Magento\Framework\App\ObjectManager::getInstance()->get('Magento\Sales\Model\OrderFactory')->create()->getCollection()->addFieldToFilter('created_at', ['from'=>$fromDate])->getSize();		
		$orders['30_days']['cancelled'] = \Magento\Framework\App\ObjectManager::getInstance()->get('Magento\Sales\Model\OrderFactory')->create()->getCollection()->addFieldToFilter('state', \Magento\Sales\Model\Order::STATE_CANCELED )->addFieldToFilter('created_at', ['from'=>$fromDate])->getSize();
		$orders['30_days']['refunded'] = \Magento\Framework\App\ObjectManager::getInstance()->get('Magento\Sales\Model\OrderFactory')->create()->getCollection()->addFieldToFilter('state', \Magento\Sales\Model\Order::STATE_CLOSED)->addFieldToFilter('created_at', ['from'=>$fromDate])->getSize();		
		
		$query = 'SELECT count(*) total_ontime FROM ' . $order_table . " WHERE `created_at` >= DATE_SUB(Now(), INTERVAL 30 DAY) AND HOUR(TIMEDIFF(created_at, updated_at)) < 48 AND state = '".\Magento\Sales\Model\Order::STATE_COMPLETE."' LIMIT 1";
		$orders['30_days']['ontime_shipment'] = $readConnection->fetchOne($query);
		
		$query = 'SELECT count(*) total_return FROM ' . $creditmemo_table . " WHERE `created_at` >= DATE_SUB(Now(), INTERVAL 30 DAY) LIMIT 1";
		$orders['30_days']['returns'] = $readConnection->fetchOne($query);
		
		/*get 90 days records*/
		$fromDate = date('Y-m-d H:i:s', strtotime('-90 days'));
		$orders['90_days']['total'] = \Magento\Framework\App\ObjectManager::getInstance()->get('Magento\Sales\Model\OrderFactory')->create()->getCollection()->addFieldToFilter('created_at', ['from'=>$fromDate])->getSize();		
		$orders['90_days']['cancelled'] = \Magento\Framework\App\ObjectManager::getInstance()->get('Magento\Sales\Model\OrderFactory')->create()->getCollection()->addFieldToFilter('state', \Magento\Sales\Model\Order::STATE_CANCELED )->addFieldToFilter('created_at', ['from'=>$fromDate])->getSize();
		$orders['90_days']['refunded'] = \Magento\Framework\App\ObjectManager::getInstance()->get('Magento\Sales\Model\OrderFactory')->create()->getCollection()->addFieldToFilter('state', \Magento\Sales\Model\Order::STATE_CLOSED)->addFieldToFilter('created_at', ['from'=>$fromDate])->getSize();		
		
		$query = 'SELECT count(*) total_ontime FROM ' . $order_table . " WHERE `created_at` >= DATE_SUB(Now(), INTERVAL 90 DAY) AND HOUR(TIMEDIFF(created_at, updated_at)) < 48 AND state = '".\Magento\Sales\Model\Order::STATE_COMPLETE."' LIMIT 1";
		$orders['90_days']['ontime_shipment'] = $readConnection->fetchOne($query);
		
		$query = 'SELECT count(*) total_return FROM ' . $creditmemo_table . " WHERE `created_at` >= DATE_SUB(Now(), INTERVAL 90 DAY) LIMIT 1";
		$orders['90_days']['returns'] = $readConnection->fetchOne($query);
		
		/*get 1 year records*/
		$fromDate = date('Y-m-d H:i:s', strtotime('-1 year'));
		$orders['1_year']['total'] = \Magento\Framework\App\ObjectManager::getInstance()->get('Magento\Sales\Model\OrderFactory')->create()->getCollection()->addFieldToFilter('created_at', ['from'=>$fromDate])->getSize();		
		$orders['1_year']['cancelled'] = \Magento\Framework\App\ObjectManager::getInstance()->get('Magento\Sales\Model\OrderFactory')->create()->getCollection()->addFieldToFilter('state', \Magento\Sales\Model\Order::STATE_CANCELED )->addFieldToFilter('created_at', ['from'=>$fromDate])->getSize();
		$orders['1_year']['refunded'] = \Magento\Framework\App\ObjectManager::getInstance()->get('Magento\Sales\Model\OrderFactory')->create()->getCollection()->addFieldToFilter('state', \Magento\Sales\Model\Order::STATE_CLOSED)->addFieldToFilter('created_at', ['from'=>$fromDate])->getSize();		
		
		$query = 'SELECT count(*) total_ontime FROM ' . $order_table . " WHERE `created_at` >= DATE_SUB(Now(), INTERVAL 1 YEAR) AND HOUR(TIMEDIFF(created_at, updated_at)) < 48 AND state = '".\Magento\Sales\Model\Order::STATE_COMPLETE."' LIMIT 1";
		$orders['1_year']['ontime_shipment'] = $readConnection->fetchOne($query);
		
		$query = 'SELECT count(*) total_return FROM ' . $creditmemo_table . " WHERE `created_at` >= DATE_SUB(Now(), INTERVAL 1 YEAR) LIMIT 1";
		$orders['1_year']['returns'] = $readConnection->fetchOne($query);
		
		$orders_json = json_encode($orders);
		echo $orders_json;
    }
}
