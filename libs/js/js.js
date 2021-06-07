
function showPage() {
    $("#loader-wrapper").fadeOut(500, function () {
        // fadeOut complete. Remove the loading div
        $("#loader-wrapper").remove(); //makes page more lightweight 
        document.getElementById("main").style.display = "block";
    });
}

function hidePage() {
    document.getElementById("loader-wrapper").style.display = "block";
    document.getElementById("main").style.display = "none";
}

var cityIcon = L.icon.fontAwesome({
    iconClasses: "far fa-building",
    markerColor: '#F3D849',
    markerFillOpacity: 0.95,
    markerStrokeWidth: 1,
    markerStrokeColor: "grey",
    iconColor: "black",
    iconXOffset: -1,
    iconYOffset: -10
})

var touristIcon = L.icon.fontAwesome({
    iconClasses: "fa fa-info-circle",
    markerColor: '#0A62D0',
    markerFillOpacity: 0.95,
    markerStrokeWidth: 1,
    markerStrokeColor: "grey",
    iconColor: "#FFF",
    iconXOffset: -2,
    iconYOffset: -10
})

let searchOptions = $('#search');

searchOptions.empty();
searchOptions.append('<option disabled>Choose a Country</option>');
searchOptions.prop('selectedIndex', 0);

function populateCountries() {
    $.ajax({
        type: 'GET',
        url: 'libs/php/country.php',
        dataType: 'json',
        success: function (result) {
            for (let i = 0; i < result.data.length; i++) {
                searchOptions.append($('<option></option>').attr('value', result.data[i].code).text(result.data[i].name)).select2();
            }
        }
    })
}

populateCountries();

let currencyOptions = $('.currencyOptions');

currencyOptions.empty();
currencyOptions.append('<option selected="true" disabled>Choose Currency</option>');
currencyOptions.prop('selectedIndex', 0);

function populateCurrencies() {
    $.getJSON("libs/php/currencies.json", function (currencies) {
        $.each(currencies, function (key, entry) {
            currencyOptions.append($('<option></option>').attr('value', entry.code).text(`${entry.name} (${entry.symbol})`));
        })
    });
}

populateCurrencies();

var coordinates = [];

function coords2country() {
    $.ajax({
        type: 'POST',
        url: 'libs/php/postCountry.php',
        data: {
            lng: coordinates[1],
            lat: coordinates[0]
        },
        dataType: 'json',
        success: function (result) {

            console.log(result);

            $("#search").val(result.openCage.results[0].components["ISO_3166-1_alpha-2"]).trigger('change');
        }
    })
}

function showPosition(position) {
    let lat = position.coords.latitude;
    let lng = position.coords.longitude;
    coordinates.push(lat);
    coordinates.push(lng);
    coords2country();
}

function getLocation() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(showPosition);
    } else {
        console.log("Geolocation is not supported by this browser.");
        defaultLocation();
    }
}

function defaultLocation() {
    alert('Using developer\s default location.');
    coordinates.push(51.5073219);
    coordinates.push(-0.1276474);
    coords2country();
}

var streetview = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Street_Map/MapServer/tile/{z}/{y}/{x}', {
    attribution: 'Tiles &copy; Esri &mdash; Source: Esri, DeLorme, NAVTEQ, USGS, Intermap, iPC, NRCAN, Esri Japan, METI, Esri China (Hong Kong), Esri (Thailand), TomTom, 2012'
});

var satellite = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
    attribution: 'Tiles &copy; Esri &mdash; Source: Esri, i-cubed, USDA, USGS, AEX, GeoEye, Getmapping, Aerogrid, IGN, IGP, UPR-EGP, and the GIS User Community'
});

var baseMaps = {
    "Street View": streetview,
    "Satellite": satellite
};


let map = L.map('map', {
    layers: [streetview]
});

map.options.minZoom = 2.25;
map.zoomControl.setPosition('topright');

L.control.layers(baseMaps).addTo(map);

var info = L.easyButton({
    position: 'topleft',
    id: 'info_modal_button',
    states: [{
        icon: '<i class="fas fa-info"></i>',
        title: 'Info',
        onClick: function (control) { $('#info').modal('show') },
    }]
})

info.addTo(map);

var wiki = L.easyButton({
    position: 'topleft',
    id: 'wiki_modal_button',
    states: [{
        icon: '<i class="fab fa-wikipedia-w"></i>',
        title: 'Wikipedia',
        onClick: function (control) { $('#wikiSummary').modal('show') },
    }]
})

wiki.addTo(map);

var exchange = L.easyButton({
    position: 'topleft',
    id: 'exchange_modal_button',
    states: [{
        icon: '<i class="fas fa-coins"></i>',
        title: 'Exchange',
        onClick: function (control) { $('#exchange').modal('show') },
    }]
})

exchange.addTo(map);

var weather = L.easyButton({
    position: 'topleft',
    id: 'weather_modal_button',
    states: [{
        icon: '<i class="fas fa-cloud-sun-rain"></i>',
        title: 'Weather',
        onClick: function (control) { $('#weather').modal('show') },
    }]
})

weather.addTo(map);

var news = L.easyButton({
    position: 'topleft',
    id: 'news_modal_button',
    states: [{
        icon: '<i class="far fa-newspaper"></i>',
        title: 'News',
        onClick: function (control) { $('#news').modal('show') },
    }]
})

news.addTo(map);

var covid = L.easyButton({
    position: 'topleft',
    id: 'covid_modal_button',
    states: [{
        icon: '<i class="fas fa-lungs-virus"></i>',
        title: 'Covid',
        onClick: function (control) { $('#covid').modal('show') },
    }]
})

covid.addTo(map);

function displayMap() {
    map.setView([51.5073219, -0.1276474], 13);

    window.setTimeout(function () {
        map.invalidateSize();
    }, 1000);

    $('#location').modal('show')
}

var markers;
var countryBorders;

var geojson;

function getCountryBorder() {
    $.ajax({
        type: 'POST',
        url: 'libs/php/postCountryBorder.php',
        data: {
            iso: $('#search').val()
        },
        dataType: 'json',
        success: function (result) {

            console.log(result);

            if (countryBorders || markers) {
                countryBorders.clearLayers();
                markers.clearLayers();
            }

            //mapCoutryBorder
            var geojsonFeature = result.geom;

            countryBorders = L.geoJSON(geojsonFeature, {
                color: '#191718'
            }).addTo(map);
        }
    })
}

function preloadImage(documentLocation, width, height) {
    var img = new Image(width, height);
    img.alt = 'Image of the article';
    img.src = documentLocation;
    return img;
}

var capital = [];

const nth = function (d) {
    if (d > 3 && d < 21) return 'th';
    switch (d % 10) {
        case 1: return "st";
        case 2: return "nd";
        case 3: return "rd";
        default: return "th";
    }
}

function getWeather() {
    $.ajax({
        type: 'POST',
        url: 'libs/php/postWeather.php',
        data: {
            capital: capital[0]
        },
        dataType: 'json',
        success: function (result) {

            console.log(result);

            var openWeather = result.openWeather;
            var openCage = result.openCage.results[0];

            document.querySelector("#country_weather").innerText = "Weather in " + capital[0] + ", " + openCage.components.country;

            const speed_ms = (openWeather.daily[0].wind_speed).toFixed(2);
            const speed_mph = (speed_ms * 2.237).toFixed(2);
            const humidity = openWeather.daily[0].humidity;

            document.querySelector("#day_0_humidity").innerText = "Humidity: " + humidity + " %";
            document.querySelector("#day_0_speed_ms").innerText = "Wind speed: " + speed_ms + " m/s";
            document.querySelector("#day_0_speed_mph").innerText = "Wind speed: " + speed_mph + " mph";

            for (let i = 0; i < 5; i++) {

                function addDays(date, days) {
                    var result = new Date(date);
                    result.setDate(result.getDate() + days);
                    return result;
                }

                var options = { weekday: 'short', day: 'numeric' };
                var today = new Date();
                var day = addDays(today, i);
                var nthoptions = { day: 'numeric' }

                document.querySelector("#day_" + i).innerHTML = day.toLocaleDateString("en-GB", options) + `<sup> ${nth(day.toLocaleDateString("en-GB", nthoptions))}</sup>`;

    
                const celsius_temp_max = (openWeather.daily[i].temp.max).toFixed(2);
                const fahrenheit_temp_max = ((celsius_temp_max * 1.8) + 32).toFixed(2);
                const celsius_temp_min = (openWeather.daily[i].temp.min).toFixed(2);
                const fahrenheit_temp_min = ((celsius_temp_min * 1.8) + 32).toFixed(2);

                document.querySelector("#day_" + i + "_icon").setAttribute('src', `https://openweathermap.org/img/wn/${openWeather.daily[i].weather[0].icon}@2x.png`);
                document.querySelector("#day_" + i + "_max_temp_celsius").innerHTML = celsius_temp_max + ' <sup>o</sup>C';
                document.querySelector("#day_" + i + "_max_temp_fahrenheit").innerHTML = fahrenheit_temp_max + ' <sup>o</sup>F';
                document.querySelector("#day_" + i + "_min_temp_celsius").innerHTML = celsius_temp_min + ' <sup>o</sup>C';
                document.querySelector("#day_" + i + "_min_temp_fahrenheit").innerHTML = fahrenheit_temp_min + ' <sup>o</sup>F';
            }

            capital.pop();
        }
    })
}

function getInfo() {
    getCountryBorder();
    map.spin(true, { color: '#E8AE71' });
    $.ajax({
        type: 'POST',
        url: 'libs/php/postInfo.php',
        data: {
            country: $('#search option:selected').text(),
            iso: $('#search').val(),
        },
        dataType: 'json',
        success: function (result) {

            console.log(result);

            //openCage
            var openCage = result.openCage.results[0];

            var bounds = [
                [openCage.bounds.northeast.lat, openCage.bounds.northeast.lng],
                [openCage.bounds.southwest.lat, openCage.bounds.southwest.lng]
            ];

            coordinates.pop();
            coordinates.pop();
            coordinates.push(openCage.geometry.lat);
            coordinates.push(openCage.geometry.lng);

            map.flyTo(new L.LatLng(coordinates[0], coordinates[1]), 13);
            window.setTimeout(function () {
                map.invalidateSize();
            }, 1000);

            map.fitBounds(bounds);

            //markers
            markers = L.markerClusterGroup();

            var tourists = result.tourists.geonames;

            for (let i in tourists) {

                if (tourists[i].countryCode == $('#search').val()) {

                    if (tourists[i].thumbnailImg != null) {

                        tourists[i].thumbnailImg.replace('http://', 'https://');

                        var picture = preloadImage(tourists[i].thumbnailImg, 100, 80);
                    } else {
                        var picture = preloadImage('libs/css/image/wiki.jpg', 100, 80);
                    }

                    var popup = document.createElement('div');
                    var a = document.createElement('a');
                    var br = document.createElement('br');
                    var linkText = document.createTextNode(tourists[i].title);
                    a.appendChild(linkText);
                    a.title = 'Wikipedia article';
                    a.target = '_blank';
                    a.href = 'https://' + tourists[i].wikipediaUrl;

                    popup.appendChild(picture);
                    popup.appendChild(br);
                    popup.appendChild(a);

                    markers.addLayer(
                        L.marker([tourists[i].lat, tourists[i].lng], {
                            icon: touristIcon
                        })
                            .bindPopup(popup)
                    )
                }
            }


            // Cities
            var cities = result.cities.geonames;

            for (let i in cities) {

                if (cities[i].countrycode == $('#search').val()) {

                    markers.addLayer(
                        L.marker([cities[i].lat, cities[i].lng], { icon: cityIcon }).bindPopup(`<b>${cities[i].name}</b> <br> Population: ${cities[i].population.toLocaleString()}`)
                    )
                }
            }

            map.addLayer(markers);

            //geoNames
            var geoNames = result.geoNames.geonames[0];
            $('#country').html(geoNames.countryName);
            $('#currency').html(geoNames.currencyCode);
            $('#capital').html(geoNames.capital);
            $('#population').html(parseInt(geoNames.population).toLocaleString('en-GB'));
            $('#area').html(parseInt(geoNames.areaInSqKm).toLocaleString('en-GB'));

            $('#Region').html(geoNames.continentName);
            $('#populationUnit').html('');
            $('#areaUnits').html('');

            capital.push(geoNames.capital);

            // restCountries
            var restCountries = result.restCountries;
            $('#flag').attr("src", restCountries.flag);
            $('#symbol').html(restCountries.currencies[0].symbol);
            $('#time_zone').html(restCountries.timezones);

            document.getElementById("currency_from").value = restCountries.currencies[0].code;

            // openExchange
            var openExchange = result.openExchange.rates;

            const select = document.getElementById('exchange').querySelectorAll('select');
            const input = document.getElementById('exchange').querySelectorAll('input');

            function convert(i, j) {
                input[i].value = (input[j].value * openExchange[select[i].value] / openExchange[select[j].value]).toFixed(2);
            }

            input[0].addEventListener('keyup', () => convert(1, 0));
            input[1].addEventListener('keyup', () => convert(0, 1));
            select[0].addEventListener('change', () => convert(1, 0));
            select[1].addEventListener('change', () => convert(0, 1));

            //geoNamesWiki
            var geoNamesWiki = result.geoNamesWiki;
            for (let j = 0; j < 30; j++) {
                if (geoNamesWiki.geonames[j].feature == 'country' &&
                    (geoNamesWiki.geonames[j].countryCode == openCage.components["ISO_3166-1_alpha-2"] ||
                        geoNamesWiki.geonames[j].title.includes(openCage.components.country))) {
                    $('#summary').html(geoNamesWiki.geonames[j].summary);
                    $('#wikiLink').html(geoNamesWiki.geonames[j].wikipediaUrl).attr("href", "https://" + geoNamesWiki.geonames[j].wikipediaUrl);
                    break;
                } else {
                    $('#summary').html('Beep Boop Beep! I can\t find the wikipedia page with the API! :-( \n Anyways here is more info on ' + openCage.components.country + ':');
                    $('#wikiLink').html('https://en.wikipedia.org/wiki/' + encodeURI(openCage.components.country)).attr("href", 'https://en.wikipedia.org/wiki/' + encodeURI(openCage.components.country));
                }
            }

            //covid

            var covid = result.covid;

            if (covid != null) {

                $('#total').html(covid.cases.toLocaleString('en-GB'));
                $('#recovered').html(covid.recovered.toLocaleString('en-GB'));
                $('#deaths').html(covid.deaths.toLocaleString('en-GB'));
                $('#todayCases').html(covid.todayCases.toLocaleString('en-GB'));
                $('#todayRecovered').html(covid.todayRecovered.toLocaleString('en-GB'));
                $('#todayDeaths').html(covid.todayDeaths.toLocaleString('en-GB'));
            }

            //news

            var news = result.news.articles;


            if (news.length !== 0) {

                for (let i = 0; i < 5; i++) {

                    if (document.querySelector("#day_" + (i + 1) + "_picture").hasChildNodes()) {
                        document.querySelector("#day_" + (i + 1) + "_picture").removeChild(document.querySelector("#day_" + (i + 1) + "_picture").childNodes[0]);
                    }

                    if (news[i].urlToImage != null) {
                        var news_picture = preloadImage(news[i].urlToImage, 300, 200);
                    } else {
                        var news_picture = preloadImage('libs/css/image/newspaper.jpg', 250, 200);
                    }

                    const title = news[i].title;
                    const url = news[i].url;
                    const author = news[i].source.name;

                    document.querySelector("#day_" + (i + 1) + "_title").innerHTML = title;
                    document.querySelector("#day_" + (i + 1) + "_picture").appendChild(news_picture);
                    $("#day_" + (i + 1) + '_newsLink').attr("href", url);
                    document.querySelector("#day_" + (i + 1) + "_author").innerHTML = author;
                }
            } else {
                for (let i = 0; i < 5; i++) {
                    document.querySelector("#day_" + (i + 1) + "_title").style.display = 'none';
                    document.querySelector("#day_" + (i + 1) + "_picture").style.display = 'none';
                    document.querySelector("#day_" + (i + 1) + '_newsLink').style.display = 'none';
                    document.querySelector("#day_" + (i + 1) + "_author").style.display = 'none';

                    document.querySelector("#day_" + (i + 1) + "_title_default").style.display = "block";
                    document.querySelector("#day_" + (i + 1) + "_picture_default").style.display = "block";
                    document.querySelector("#day_" + (i + 1) + '_newsLink_default').style.display = "block";
                    document.querySelector("#day_" + (i + 1) + "_author_default").style.display = "block";
                }
            }
            getWeather();
            map.spin(false);
        }        
            
    })
}

function degreeToggle() {
    if (document.getElementById('degreeToggle').innerText == 'Metric') {
        document.getElementById('degreeToggle').innerText = 'Imperial';
        var metricEle = document.getElementsByClassName('metric');
        for (var i = 0; i < metricEle.length; i++) {
            metricEle[i].style.display = "none";
            }
        var imperialEle = document.getElementsByClassName('imperial');
        for (var i = 0; i < imperialEle.length; i++) {
            imperialEle[i].style.display = "block";
        }
    } else {
        document.getElementById('degreeToggle').innerText = 'Metric';
        var metricEle = document.getElementsByClassName('metric');
        for (var i = 0; i < metricEle.length; i++) {
            metricEle[i].style.display = "block";
        }
        var imperialEle = document.getElementsByClassName('imperial');
        for (var i = 0; i < imperialEle.length; i++) {
            imperialEle[i].style.display = "none";
        }
    }
}

window.addEventListener('load', (event) => {
    hidePage();
    displayMap();
    showPage();
});