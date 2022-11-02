<?php

declare(strict_types = 1);

namespace Sewega\Signator\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Sewega\Signator\Signator;
use Sewega\Signator\SignedLink;

class SignedLinksController extends Controller
{

    public function __construct(private Signator $signator){}

    public function index(Request $request, SignedLink $signedLink): mixed
    {
        return $this->signator->handleRequest($signedLink, $request);
    }

    public function store(Request $request, SignedLink $signedLink): mixed
    {
        return $this->signator->handleRequest($signedLink, $request);
    }

    public function update(Request $request, SignedLink $signedLink): mixed
    {
        return $this->signator->handleRequest($signedLink, $request);
    }

    public function destroy(Request $request, SignedLink $signedLink): mixed
    {
        return $this->signator->handleRequest($signedLink, $request);
    }

}
