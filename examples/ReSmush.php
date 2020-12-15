<?php

use LightAir\Utils\EasyCurl;

/**
 * Example usage
 */
class ReSmush
{
    /**
     * @return void
     */
    public function baseGet(): void
    {
        $eCurl = new EasyCurl('http://api.resmush.it/ws.php');

        var_dump(
            json_decode(
                $eCurl->get()
            ),
            $eCurl->getHttpStatusCode()
        );
    }

    /**
     * @return void
     */
    public function sendImageGet(): void
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

    /**
     * @return void
     */
    public function sendImagePost(): void
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
