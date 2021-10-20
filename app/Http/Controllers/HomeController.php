<?php

namespace App\Http\Controllers;

class HomeController extends Controller
{
    public function index()
    {
        echo "index method";
    }
    public function create()
    {
        echo "create method";
    }
    public function store()
    {
        echo "store method";
    }
    public function edit($id)
    {
        echo "edit method";
    }
    public function update($id)
    {
        echo "update method";
    }
    public function destroy($id)
    {
        echo "destroy method";
    }
}
