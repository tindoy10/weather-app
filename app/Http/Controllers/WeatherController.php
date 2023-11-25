<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;

class WeatherController extends Controller
{
    public function index()
    {
        return view('weather');
    }

    public function getWeather(Request $request)
    {
        $city = $request->input('city');
        $apiKey = '3b4a7f68e257de658d5f98c9af847b3f'; // API Key
            
        $client = new Client();
        $response = $client->get("http://api.openweathermap.org/data/2.5/weather?q=$city&appid=$apiKey");
        $data = json_decode($response->getBody(), true);

        return view('weather', ['data' => $data]);
    }
}
