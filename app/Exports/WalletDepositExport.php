<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class WalletDepositExport implements FromArray, WithHeadings
{
    /**
     * @var array
     */
    private $data;

    /**
     * @param  array  $data
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * @return array
     */
    public function array(): array
    {
        return $this->data;
    }

    public function headings(): array
    {
        return [
            'User Id',
            'Current Balance',
            'Deposit Amount'
        ];
    }
}
