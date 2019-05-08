<?php

class DeviceWeights
{
    private $xml;

    public function __construct()
    {

    }

    public function deviceListInfoSummary($url) {
        try {
            if (!filter_var($url, FILTER_VALIDATE_URL) && !file_exists($url)) {
                throw new InvalidArgumentException('Invalid argument applied');
            }
        } catch(InvalidArgumentException $e) {
            error_log("ERROR: " . $e->getMessage());
            return false;
        }

        try {
            $file = file_get_contents($url);
            if (!$file) {
                throw new RuntimeException("Could not parse file");
            }
            $this->xml = new SimpleXMLElement($file);
            if (!$this->xml) {
                new RuntimeException('Could not parse as XML');
            }
        } catch (RuntimeException $e) {
            error_log("ERROR: " . $e->getMessage());
            return false;
        }


        $this->xml->registerXPathNamespace('f', 'http://main.g2planet.com/codetest/example');
        $result = $this->xml->xpath('//f:device');
        $count = null;
        $min = null;
        $max = null;
        $average = null;
        $stdDev = null;
        $weightStorage = [];

        foreach ($result as $device) {
            $count += $device['quantity'];
            for ($i = 0; $i < $device['quantity']; $i++) {
                if (strcmp($device->children('f', true)->weight->attributes()->units, 'pounds') === 0) {
                    $weight = (double) $this->poundsToOunces($device->children('f', true)->weight) . "\n";
                } else {
                    $weight =  $device->children('f', true)->weight;
                }
                array_push($weightStorage, (double) $weight);
            }

        }

        return [
            'min' => min($weightStorage),
            'max' => max($weightStorage),
            'count' => sizeof($weightStorage),
            'average' => array_sum($weightStorage) / $count,
            'stddev' => $this->standardDeviation($weightStorage, $count, $average)
        ];
    }

    private function poundsToOunces($pounds) {
        return (double) $pounds * 16;
    }

    private function standardDeviation($array, $count, $average) {
        $variance = 0.0;
        foreach ($array as $data) {
            $variance += pow(($data - $average), 2);
        }
        return (float)sqrt($variance/$count);
    }
}