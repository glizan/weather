<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

    <title>html php weather page</title>
    </script>





</head>

<body>
    <?php
    function currentWeatherDisplay()
    {


        $currentWeather = array(
            "warningstext" => "",
            "warningslevel" => "",
            "warningslink" => "",
            "station" => "",
            "dateTime" => "",
            "normalHighTemp" => "",
            "normalLowTemp" => "",
            "currentGifID" => "",
            "currentCondition" => "",
            "currentPressure" => "",
            "currentDewPoint" => "",
            "currentHumidity" => "",
            "currentWind" => "",
            "currentWindGust" => "",
            "currentVisibility" => ""
        );
        /* actually a half day but.....  */

        $forecastDay = array(
            "dayOfWeek" => "",
            "Date" => "",
            "period" => "",
            "abbrTextSummary" => "",
            "abbrPOP" => "",
            "abbviconCode" => "",
            "temperature" => "",
            "abbrTextSummary2" => "",
            "uvIndex" => "",
        );

        $forecast6Day = array(
            array($forecastDay),
            array($forecastDay),
            array($forecastDay),
            array($forecastDay),
            array($forecastDay),
            array($forecastDay),
            array($forecastDay),
            array($forecastDay),
            array($forecastDay),
            array($forecastDay),
            array($forecastDay),
            array($forecastDay),

        );





        readXMLWeather($currentWeather, $forecast6Day);
        displayCurrentWeather($currentWeather);
        displayForecast($forecast6Day);
    }

    function readXMLWeather(&$currentWeather, &$forecast6Day)
    {
        $xmldata = simplexml_load_file("https://dd.weather.gc.ca/citypage_weather/xml/BC/s0000792_e.xml") or die("Failed to load");
        currentConditionRead($xmldata, $currentWeather);
        forecastRead($xmldata, $forecast6Day);
    }

    function currentConditionRead($xmldata, &$currentWeather)
    {
        $currentWeather['warningstext'] = $xmldata->warnings->event['description'];
        $currentWeather['warningslevel'] = $xmldata->warnings->event['level'];
        $currentWeather['warningslink'] = $xmldata->warnings['url'];

        $currentWeather['station'] = $xmldata->currentConditions->station;
        $currentWeather['dateTime'] = $xmldata->currentConditions->dateTime[1]->textSummary;
        $currentWeather['normalHighTemp'] = $xmldata->currentConditions->station;
        $currentWeather['normalLowTemp'] = $xmldata->currentConditions->station;
        $currentWeather['currentTemp'] =  $xmldata->currentConditions->temperature . " " . $xmldata->currentConditions->temperature['units'];
        $currentWeather['currentGifID'] = $xmldata->currentConditions->iconCode;
        $currentWeather['currentCondition'] = $xmldata->currentConditions->condition;
        $currentWeather['currentPressure'] = $xmldata->currentConditions->pressure . " " . $xmldata->currentConditions->pressure['units'] . " " . $xmldata->currentConditions->pressure['tendency'];
        $currentWeather['currentDewPoint'] = $xmldata->currentConditions->dewpoint . " " . $xmldata->currentConditions->dewpoint['units'];
        $currentWeather['currentHumidity'] = $xmldata->currentConditions->relativeHumidity . " " . $xmldata->currentConditions->relativeHumidity['units'];
        $currentWeather['currentVisibility'] = $xmldata->currentConditions->visibility . " " . $xmldata->currentConditions->visibility['units'];
        $currentWeather['currentWind'] = $xmldata->currentConditions->wind->direction . " " .
            $xmldata->currentConditions->wind->speed  . " " .
            $xmldata->currentConditions->wind->speed['units'];
        if ("" != $xmldata->currentConditions->wind->gust) {
            $currentWeather['currentWindGust'] = $xmldata->currentConditions->wind->gust  . " " .
                $xmldata->currentConditions->wind->gust['units'];
        } else {
            $currentWeather['currentWindGust'] = "minimal";
        }
    }

    function forecastRead($xmldata, &$forecast6Day)
    {
        for ($i = 0; $i < 12; $i++) {
            $forecast6Day[$i]['dayOfWeek'] = $xmldata->forecastGroup->forecast[$i]->period;
            $forecast6Day[$i]['period'] = $xmldata->forecastGroup->forecast[$i]->period;
            $forecast6Day[$i]['temperature'] = $xmldata->forecastGroup->forecast[$i]->temperatures->temperature . "&deg" . $xmldata->forecastGroup->forecast[$i]->temperatures->temperature['units'];
            $forecast6Day[$i]['abbrTextSummary'] = $xmldata->forecastGroup->forecast[$i]->abbreviatedForecast->textSummary;
            $forecast6Day[$i]['abbrPOP'] = $xmldata->forecastGroup->forecast[$i]->abbreviatedForecast->pop;
            $forecast6Day[$i]['abbviconCode'] = $xmldata->forecastGroup->forecast[$i]->abbreviatedForecast->iconCode;
            $forecast6Day[$i]['uvIndex'] = $xmldata->forecastGroup->forecast[$i]->uv->textSummary;
        }
    }

    function displayCurrentWeather($currentWeather)
    {
        echo
            '<div class="container">
                <div class="row justify-content-md-center">
                    <div class="col col-lg-2"> 
                    </div>              
                    <div class="col-md-auto border">
                        <div class="row justify-content-md-center text-center">
                            <a href="' . $currentWeather['warningslink'] . '" class="text-danger" target="_blank" rel="noopener noreferrer">' . $currentWeather['warningstext'] . '</a>
                        </div>    
                        <div class="row justify-content-md-center">
                            Location: ' . $currentWeather['station'] . '
                        </div>    
                        <div class="row justify-content-md-center">    
                            Date: ' . $currentWeather['dateTime'] . '
                        </div>
                        <div class="row justify-content-md-center">
                            <div class="text-center">
                                <img src="https://meteo.gc.ca/weathericons/' . $currentWeather['currentGifID'] .  '.gif" alt="Partly Cloudy" width="60" height="51"></img>
                                <p class="text-center">' . $currentWeather['currentCondition'] . '</p>
                            </div>
                        </div>

                        <div class="row justify-content-md-center">
                            Temperature: ' . $currentWeather['currentTemp'] . '
                        </div>    
                        <div class="row justify-content-md-center">
                            Pressure: ' . $currentWeather['currentPressure'] . '
                        </div>    
                        <div class="row justify-content-md-center">
                            Dew point: ' . $currentWeather['currentDewPoint'] . '
                        </div>    
                        <div class="row justify-content-md-center">
                            Humidity: ' . $currentWeather['currentHumidity'] . '
                        </div>    
                        <div class="row justify-content-md-center">
                            Wind: ' . $currentWeather['currentWind'] . '
                        </div>    
                        <div class="row justify-content-md-center">
                            Wind Gusts: ' . $currentWeather['currentWindGust'] . '
                        </div>    
                        <div class="row justify-content-md-center">
                            Visibility: ' . $currentWeather['currentVisibility'] . '
                        </div>    
                    </div>
                    <div class="col col-lg-2"> 
                    </div>              
                </div>
            </div>';
    };

    function displayForecast($forecast6Day)
    {
        echo '<div class="container"  >
        <div class="row justify-content-md-center border ">';

        for ($i = 0; $i < 12; $i = $i + 2) {

            echo '<div class="col-sm-2 border">
                                <div class="row h-50 border-bottom text-center justify-content-center">
                                    <p class=""><span class="">' .  $forecast6Day[$i]["dayOfWeek"] . '</span></p>

                                    <img src="https://meteo.gc.ca/weathericons/' . $forecast6Day[$i]['abbviconCode'] . '.gif" alt="Mainly cloudy" class="center-block " style="height:51;  width: auto;"  >
                                    <p class=""><span class="low wxo-metric-hide" title="min">' . $forecast6Day[$i]['temperature'] . '</span></p>
                                    <p class="">' . $forecast6Day[$i]["abbrTextSummary"] . '</p>
                      
                                </div>
                            
                                <div class="row h-50 text-center justify-content-center">
                                    <div class="row  h-25">
                                        <p class=""><span class="">' .  $forecast6Day[$i + 1]["dayOfWeek"] . '</span></p>
                                    </div>
                                    <img src="https://meteo.gc.ca/weathericons/' . $forecast6Day[$i + 1]['abbviconCode'] . '.gif" alt="Mainly cloudy" class="center-block" style="height:51; width: auto;" >
                                    <p class=""><span class="low wxo-metric-hide" title="min">' . $forecast6Day[$i + 1]['temperature'] . '</span></p>
                                    <p class="">' . $forecast6Day[$i + 1]["abbrTextSummary"] . '</p>
                      
                                </div>
                            </div>';
        }
       echo  '</div> </div> ';

    }


?>





    <?php currentWeatherDisplay(); ?>

    <!--
    
<nav class="nav justify-content-center|justify-content-end">
  <a class="nav-link active" href="#">Active link</a>
  <a class="nav-link" href="#">Link</a>
  <a class="nav-link disabled" href="#">Disabled link</a>
</nav><nav class="nav justify-content-center|justify-content-end">
  <a class="nav-link active" href="#">Active link</a>
  <a class="nav-link" href="#">Link</a>
  <a class="nav-link disabled" href="#">Disabled link</a>
</nav>
-->



</body>

</html>