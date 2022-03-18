<?php

namespace Grayloon\Magento\Api;

class Invoices extends AbstractApi
{
    public function all($pageSize = 50, $currentPage = 1, $filters = [])
    {
        return $this->get('/invoices', array_merge($filters, [
            'searchCriteria[pageSize]' => $pageSize,
            'searchCriteria[currentPage]' => $currentPage,
        ]));
    }

    public function getInvoiceByOrderId($order_id, $order_value)
    {
        return $this->get('/invoices', [
            'searchCriteria[filterGroups][0][filters][0][field]' => $order_id,
            'searchCriteria[filterGroups][0][filters][0][value]' => $order_value,
        ]);
    }
}
