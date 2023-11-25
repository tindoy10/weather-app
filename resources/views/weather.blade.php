<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ClimaCheck</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <style>
        body {
            font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
            color: black;
            background-image: url("{{ asset('images/background2.jpg') }}") !important;
            background-size: cover !important;
            height: 100vh;
            overflow-y: hidden;
        }

        .weather-container {
            background: rgba(255, 255, 255, .1);
            backdrop-filter: blur(30px);
            border: 2px solid rgba(255, 255, 255, .2);
            border-radius: 16px;
        }

        .input-group input {
            border-radius: 10px;
            font-size: 18px;
        }

        .input-group input:focus {
            border: 2px #3c4d4c solid;
            box-shadow: none;
        }

        .input-group .input-icon {
            position: absolute;
            left: 2px;
            font-size: 25px;
        }

        .weather-image {
            display: block;
            margin-left: auto;
            margin-right: auto;
            width: 60%;
            max-width: 100%;
        }

        .card {
            background-color: transparent;
            border: none;
            color: black;
        }

        /* Add this to your existing styles */
        .btn {
            background-color: #3c4d4c; /* Use a shade of blue */
            color: white; /* Text color */
            border: #007BFF;
            font-size: 18px;
        }

        /* Add hover effect for better user interaction */
        .btn:hover {
            background-color: #2a3635; /* Darker shade of blue on hover */
            color: white;
        }
    </style>

      <script>
        function updateTime() {
            const now = new Date();
            const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric'};
            const formattedDate = now.toLocaleDateString('en-US', options);
            document.getElementById('currentDateTime').textContent = formattedDate;
        }

        // Update the time every second
        setInterval(updateTime, 1000);
    </script>
</head>

<body>
    <div class="container mt-4 px-5 py-4">
        <div class="row">
            <div class="col-lg-3"></div>
            <div class="col-lg-6 weather-container">
            
                <form method="post" action="{{ route('getWeather') }}">
                    @csrf
                    <div class="form-group mt-4 mb-3">
                        <div class="input-group">
                            <input type="text" name="city" class="form-control" placeholder="Enter location" required>
                            <div class="input-group-append">
                                <button type="submit" class="btn ml-auto"><i class='bx bx-search'></i></button>
                            </div>
                        </div>
                    </div>
                </form>

                @if(isset($data))          
                @php
                $temperatureCelsius = $data['main']['temp'] - 273.15;
                $description = '';
                $imagePath = '';

                if ($temperatureCelsius < 20) { 
                    $imagePath='cold2.png'; // Image for cold temperature 
                    $description = 'Cool';
                } elseif ($temperatureCelsius >= 20 && $temperatureCelsius < 30) { 
                    $imagePath='warm.png'; // Image for moderate temperature 
                    $description = 'Warm';
                } else { 
                    $imagePath='hot2.png'; // Image for hot temperature 
                    $description = 'Hot';
                } 

                // Map weather descriptions to simpler terms
                $weatherMapping = [
                    'clear sky' => 'Clear Sky',
                    'overcast clouds' => 'Overcast Clouds',
                    'scattered clouds' => 'Scattered Clouds',
                    'broken clouds' => 'Broken Clouds',
                    // Add more mappings as needed
                ];

                // Get the weather description from the API response
                $weatherDescription = strtolower($data['weather'][0]['description']);

                // Use the mapping or default to the original description
                $simpleWeather = $weatherMapping[$weatherDescription] ?? ucfirst($weatherDescription);
                
                // Fetch the full country name using REST Countries API
                $countryCode = $data['sys']['country'];
                $countryApiUrl = "https://restcountries.com/v2/alpha/$countryCode";
                $countryApiResponse = json_decode(file_get_contents($countryApiUrl), true);
                $fullCountryName = $countryApiResponse['name'] ?? $countryCode;

                // Concatenate city and country
                $location = $data['name'] . ', ' . $fullCountryName;
                @endphp  
                
                <div class="text-center mb-4" style="color: black;">
                    <h1 class="card-title">{{ $location }}</h1>
                    <h4 id="currentDateTime"></h4>
                </div>
                
                <img src="{{ asset('images/' . $imagePath) }}" alt="Weather Image" class="weather-image">

                <div class="card px-3">
                    <div class="card-body">
                        <p class="card-text mb-2" style="font-size: 22px;">Temperature: {{ number_format($temperatureCelsius, 2) }} °C ({{ $description }})</p>
                        <p class="card-text" style="font-size: 22px;">Weather: {{ $simpleWeather }}</p>
                    </div>
                </div>
            </div>
            @endif
        </div>
        <div class="col-lg-3"> </div>
    </div>
    </div>

    <!-- Add Bootstrap JS and Popper.js from CDN -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>

<!-- https://thinkmetric.uk/basics/temperature/ -->