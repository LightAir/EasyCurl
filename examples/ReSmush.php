<?php
/**
 * Base Class
 */

namespace LightAir\Examples;

use LightAir\EasyCurl\EasyCurl;

class ReSmush
{

    public function baseGet()
    {
        $eCurl = new EasyCurl('http://api.resmush.it/ws.php');

        dump(
            json_decode(
                $eCurl->get()
            ),
                $eCurl->getHttpStatusCode()
        );
    }

    public function sendImageGet()
    {
        $eCurl = new EasyCurl('http://api.resmush.it/ws.php');


        dump(
            json_decode(
                $eCurl->get(
                    [
                        'img' => 'https://softroot.ru/pictures/cup.png'
                    ]
                )
            ),
                $eCurl->getHttpStatusCode(),
                $eCurl->getHttpErrorMessage()
        );
    }

    public function sendImagePost()
    {
        $eCurl = new EasyCurl('http://api.resmush.it/ws.php');


        dump(
            json_decode(
                $eCurl->post(
                    [
                        'img' => 'https://softroot.ru/pictures/cup.png'
                    ]
                )
            ),
            $eCurl->getHttpStatusCode(),
            $eCurl->getHttpErrorMessage()
        );
    }


}