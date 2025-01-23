<?php

namespace App\Bots\rentcarca_bot;


class Config
{
    public static function getConfig()
    {
        return [
            'inline_data'       => [
                'body_type'    => null,
                'transmission' => null,
                'drive_type'   => null,
                'fuel_type'    => null,
                'announcement_id' => null,
                'features'  => null,
                'step' => null,
                'temp' => null
            ],
            'lang'              => 'ru',
            'admin_ids'         => [
            ],
        ];
    }
}
