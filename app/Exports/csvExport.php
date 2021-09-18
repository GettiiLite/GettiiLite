<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;

class csvExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    private $data;

    public function __construct(array $data) 
    {

        $this->data = $data;
    }
    public function collection()
    {
        return collect($this->data);
    }
}
