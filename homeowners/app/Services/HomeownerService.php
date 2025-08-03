<?php

namespace App\Services;

use App\Contracts\HomeownerDriver;
use Exception;
use Illuminate\Http\UploadedFile;

class HomeownerService implements HomeownerDriver
{
    private const DUAL_OWNER_INDICATORS = ['and', '&'];
    private const UNSANITARY_STRING_COMPONENTS = ['.'];
    private const INITIAL_STRING_COUNT = 1;

    /**
     * @throws Exception
     */
    public function getRaw(UploadedFile $file): array
    {
        $fileName = $file->getRealPath();

        $homeOwnerCsv = fopen($file->getRealPath(), 'rb');

        if (!$homeOwnerCsv) {
            throw new Exception("Unable to open csv file: " . $fileName);
        }

        fgetcsv($homeOwnerCsv);

        $rawHomeOwnerData = [];

        while ($row = fgetcsv($homeOwnerCsv)) {
            $rawHomeOwnerData[] = current($row);
        }

        return $rawHomeOwnerData;
    }

    public function process(array $homeowners): array
    {
        $processedHomeowners = [];

        foreach ($homeowners as $homeowner) {

            $homeowner = $this->sanitiseHomeowner($homeowner);

            if ($this->isDualOwner($homeowner)) {
                $allParts = explode(' ', trim($homeowner));
                $sharedLastName = end($allParts);

                $parts = preg_split('/\s+(and|&)\s+/i', $homeowner);

                foreach ($parts as $part) {
                    $personParts = explode(' ', trim($part));

                    $personData = $this->parsePersonName($personParts);

                    if ($personData['last_name'] === null) {
                        $personData['last_name'] = $sharedLastName;
                    }

                    $processedHomeowners[] = $personData;
                }
                continue;
            }

            $parts = explode(' ', trim($homeowner));
            $personData = $this->parsePersonName($parts);
            $processedHomeowners[] = $personData;
        }

        return $processedHomeowners;
    }

    private function parsePersonName(array $parts): array
    {
        $person = [
            'title' => null,
            'first_name' => null,
            'initial' => null,
            'last_name' => null
        ];

        if (!empty($parts)) {
            $person['title'] = array_shift($parts);
        }

        if (!empty($parts)) {
            $person['last_name'] = array_pop($parts);

            if (!empty($parts) && strlen(end($parts)) === self::INITIAL_STRING_COUNT) {
                $person['initial'] = end($parts);
                array_pop($parts);
            }

            if (!empty($parts)) {
                $person['first_name'] = implode(' ', $parts);
            }
        }

        return $person;
    }

    private function isDualOwner($homeowner): bool
    {
        foreach(self::DUAL_OWNER_INDICATORS as $indicator) {
            if (str_contains($homeowner, $indicator)) {
                return true;
            }
        }

        return false;
    }

    private function sanitiseHomeowner(string $homeowner): string
    {
        foreach(self::UNSANITARY_STRING_COMPONENTS as $component) {
            if (str_contains($homeowner, $component)) {
                return str_replace($component, '', $homeowner);
            }
        }

        return $homeowner;
    }
}
