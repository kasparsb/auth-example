<?php
namespace App\Services;

use DeviceDetector\DeviceDetector;
use DeviceDetector\Parser\Device\AbstractDeviceParser;

class DeviceDetect {

    public function getInfo($userAgent) {
        $dd = new DeviceDetector($userAgent);
        $dd->parse();

        if ($dd->isBot()) {
            return [
                'device' => [
                    'name' => 'bot'
                ],
                'bot' => $dd->getBot()
            ];
        }
        else {
            $os = $dd->getOs();
            $client = $dd->getClient();

            return [
                'device' => [
                    'name' => $dd->getDeviceName(),
                    'brand' => $dd->getBrandName(),
                    'model' => $dd->getModel(),
                ],
                'client' => [
                    'type' => data_get($client, 'type'),
                    'name' => data_get($client, 'name'),
                    'version' => data_get($client, 'version'),
                    'family' => data_get($client, 'family'),
                ],
                'os' => [
                    'name' => data_get($os, 'name'),
                    'version' => data_get($os, 'version'),
                    'platform' => data_get($os, 'platform'),
                ],
            ];
        }
    }
}
