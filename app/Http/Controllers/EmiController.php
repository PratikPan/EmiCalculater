<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EmiController extends Controller
{
    public function index(Request $request)
    {

        if (isset($request->_token)) {
            $data = $request->all();

            $interest = (float)$data['interest'];
            $period = (float)$data['period'];
            $loan_amount = (float)$data['amount'];
            $period = $period * 12;
            $interest = $interest / 1200;

            //---------------count Emi---------------

            $emi = $interest * -$loan_amount * pow((1 + $interest), $period) / (1 - pow((1 + $interest), $period));


            return view('index', ['request' => $data, 'emi' => $emi]);
        }
        return view('index');
    }
}
