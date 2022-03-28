<?php

namespace Grayloon\Magento\Api;

use Illuminate\Http\Client\Response;

class Addresses extends AbstractApi
{

    /**
     * @param int $id
     * @param object $body
     * @return Response|void
     */
    public function edit($id, $body)
    {
        return $this->put('/customers/' . $id, 'customer' => $body);
    }

}
