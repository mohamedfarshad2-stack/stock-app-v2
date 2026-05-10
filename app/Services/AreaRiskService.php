<?php

namespace App\Services;

use App\Models\AreaStat;

class AreaRiskService
{

    public function detect($city)
    {

        $area = AreaStat::where('city',$city)->first();

        if(!$area){
            return ['risk'=>false];
        }

        if($area->return_rate >= 40){

            return [
                'risk' => true,
                'reason' => 'High return rate area'
            ];

        }

        return [
            'risk'=>false
        ];
    }

}