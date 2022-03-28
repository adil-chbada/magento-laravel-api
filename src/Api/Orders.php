<?php

namespace Grayloon\Magento\Api;

class Orders extends AbstractApi
{
    /**
     * Lists orders that match specified search criteria.
     *
     * @param  int  $pageSize
     * @param  int  $currentPage
     * @param  array  $filters
     * @return \Illuminate\Http\Client\Response
     */
    public function all($pageSize = 50, $currentPage = 1, $filters = [])
    {
        return $this->get('/orders', array_merge($filters, [
            'searchCriteria[pageSize]'    => $pageSize,
            'searchCriteria[currentPage]' => $currentPage,
        ]));
    }

    /**
     * Loads a specified order.
     *
     * @param  int  $orderId
     * @return array
     */
    public function show($orderId)
    {
        return $this->get('/orders/'.$orderId);
    }


    public function create($body = [])
    {
        return $this->put('/orders/create', ['entity'=> $body]);
    }

    public function getOrdersByCustomer($customer_id, $customer_value)
    {
        return $this->get('/orders', [
            'searchCriteria[filterGroups][0][filters][0][field]' => $customer_id,
            'searchCriteria[filterGroups][0][filters][0][value]' => $customer_value,
        ]);
    }

    public function getOrdersByItinareaValidation($itinarea_validation, $itinarea_validation_value)
    {
        return $this->get('/orders', [
            'searchCriteria[filterGroups][0][filters][0][field]' => $itinarea_validation,
            'searchCriteria[filterGroups][0][filters][0][value]' => $itinarea_validation_value,
        ]);
    }
}
