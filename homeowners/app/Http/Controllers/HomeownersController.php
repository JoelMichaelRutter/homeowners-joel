<?php

namespace App\Http\Controllers;

use App\Contracts\HomeownerDriver;
use App\Http\Requests\StoreHomeownerDataRequest;
use Illuminate\Support\Facades\Log;

class HomeownersController extends Controller
{
    public function __construct(
        public HomeownerDriver $homeownerService
    ) {
    }

    /**
     * Would really want to tighten this up, proper custom exceptions and error handling
     * to provide context to user where they are doing something abit jank. Specifically,
     * request validates that file is of csv mime type, so would want to send them back to upload
     * page with an error to say stop trying to upload the wrong file type, but ran out of time :(
     * @throws \Exception
     */
    public function store(StoreHomeownerDataRequest $request)
    {
        try {
            $file = $request->file('homeowner_data');

            $rawHomeOwnerData = $this->homeownerService->getRaw($file);

            $processedHomeownerData = $this->homeownerService->process($rawHomeOwnerData);

            return view('processed', ['processedHomeownerData' => $processedHomeownerData]);
        } catch (\Throwable $exception) {
            Log::info($exception->getMessage());
        }
    }
}
