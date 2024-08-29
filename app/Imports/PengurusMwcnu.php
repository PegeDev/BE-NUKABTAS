<?php

namespace App\Imports;

use App\Models\Mwcnu;
use Maatwebsite\Excel\Concerns\ToModel;

class PengurusMwcnu implements ToModel
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new Mwcnu([
            //
        ]);
    }
}
