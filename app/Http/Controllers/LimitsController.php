<?php

namespace App\Http\Controllers;

use App\ConfigurationManager;

class LimitsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \int[][][]
     */
    public function index()
    {
        return ConfigurationManager::limits();
    }
}
