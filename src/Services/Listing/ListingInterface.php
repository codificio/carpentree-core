<?php

namespace Carpentree\Core\Services\Listing;

use Illuminate\Http\Request;

interface ListingInterface
{
    public function list(Request $request);
}
