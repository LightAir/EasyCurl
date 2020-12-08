<?php
/**
 * Example Class
 */

namespace LightAir\Examples;

use LightAir\EasyCurl\EasyCurl;

class ReSmush
{

    public function baseGet()
    {
        $eCurl = new EasyCurl('http://api.resmush.it/ws.php');

        var_dump(
            json_decode(
                $eCurl->get()
            ),
            $eCurl->getHttpStatusCode()
        );
    }

    public function sendImageGet()
    {
        $eCurl = new EasyCurl('http://api.resmush.it/ws.php');

        var_dump(
            json_decode(
                $eCurl->get(
                    [
                        'img' => 'https://softroot.ru/static/e16f9dccf792794dbe39f3c76864ff34/b4295/alen-jacob-wdxb9OOMQOs-unsplash.jpg'
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

        var_dump(
            json_decode(
                $eCurl->post(
                    [
                        'img' => 'https://softroot.ru/static/e16f9dccf792794dbe39f3c76864ff34/b4295/alen-jacob-wdxb9OOMQOs-unsplash.jpg'
                    ]
                )
            ),
            $eCurl->getHttpStatusCode(),
            $eCurl->getHttpErrorMessage()
        );
    }
}
