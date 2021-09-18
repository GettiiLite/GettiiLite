<?php
namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
class SeatDataImport extends \PhpOffice\PhpSpreadsheet\Cell\StringValueBinder implements WithCustomValueBinder, ToCollection
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection) 
    {
        return $collection->toArray();
    }
}