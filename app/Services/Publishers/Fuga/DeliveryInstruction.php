<?php

namespace App\Services\Publishers\Fuga;

/*
 * @property $client Client
 */

trait DeliveryInstruction
{
    public function addDsp($productId)
    {
        $albumDPSs = $this->album
            ->deliverableStores()
            ->whereNotNull('fuga_store_id')
            ->pluck('fuga_store_id');

        $dspPayload = [];

        foreach ($albumDPSs as $dsp) {
            $dspPayload[]['dsp'] = $dsp;
        }

        $response = $this->client->put('products/'.$productId.'/delivery_instructions/edit', [
            'json' => $dspPayload
        ]);

        if ($response->getStatusCode() === 200) {
            $this->log('DELIVERY_INSTRUCTION', true, 'DSP Added: '.collect($albumDPSs)->join(', '));
        } else {
            $this->log('DELIVERY_INSTRUCTION', false, '');
        }

        return $this;
    }
}
