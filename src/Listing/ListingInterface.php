<?php

namespace Carpentree\Core\Listing;

use Illuminate\Http\Request;

interface ListingInterface
{
    public function list(Request $request);
}
