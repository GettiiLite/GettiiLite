<?php
namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;
class SeatDataImport extends \PhpOffice\PhpSpreadsheet\Cell\StringValueBinder implements WithCalculatedFormulas, ToCollection
{
    /**
    * @param Collection $collection
    */
    // STS 2021/09/20 Created - Update Laravel 8
    public function collection(Collection $collection) 
    {
        return $collection->toArray();
    }
}