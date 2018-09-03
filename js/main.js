var options = {"zooming": true, maxZoom: 3};
var earth = new WE.map('earth_div', options);
var imagedata = "./app/maps/map5/{z}/{x}/{y}.jpg";
//imagedata = 'http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png';
// imagedata = 'http://data.webglearth.com/natural-earth-color/{z}/{x}/{y}.jpg';

// use for getting name of location randomly generated GPS
var GOOGLE_API_KEY = "AIzaSyC4aecVok2LRfQTqjU5zyS0vlA89v8GrHE";
var is3D = true;

var map2D, evtSource;
var log = $("#logs");
var markerinterval = 0;
var rotateinterval = 0;
var googleTitle = false;
var randomMarker = true;
var markerFromServer = true;


function initialize() {

    init3D();
    play3D();
    initKeys();

    if (googleTitle) {
        log.show();
    }

    if (randomMarker) {
        runRandomMarker()
    }

    if (markerFromServer) {
        connectMarkerServer();
    }

}

function init3D() {
    earth.setView([50.077079, 14.426828], 2.5);
    WE.tileLayer(imagedata).addTo(earth);
}

function init2D() {
    map2D = L.map('earth_div_flat', {center: [51.505, -0.09], dragging: false, zoom: 2, minZoom: 2, maxZoom: 4});
    L.tileLayer(imagedata).addTo(map2D);
}

function initKeys() {
    $("#play").on("click", function () {
        if ($(this).hasClass("irun")) {
            $(this).removeClass("irun");
            clearInterval(markerinterval);
            clearInterval(rotateinterval);
        } else {
            play3D();
            runRandomMarker();
            $(this).addClass("irun");
        }
    });

    $("#switch").on("click", function () {

        if ($(this).hasClass("i3d")) {
            $(this).removeClass("i3d");
            is3D = false;
            $("#earth_div").hide();
            $("#earth_div_flat").show();
            if (!map2D) {
                init2D();
            }
        } else {
            $(this).addClass("i3d");
            is3D = true;
            $("#earth_div").show();
            $("#earth_div_flat").hide();
            $(".leaflet-marker-icon").remove();
        }
    });

    $("#push").click(function () {
        darker(function () {
            $("#qrcode").empty().show();
            var url = window.location.protocol + "//" + window.location.host + "/" + window.location.pathname
            new QRCode(document.getElementById("qrcode"), url + "push.html");
        });
    });

    $("#qrcode").click(function () {
        $("#qrcode").empty().hide();
        undarker();
    });
}

function play3D() {

    // Start a simple rotation animation
    rotateinterval = setInterval(function () {
        var c = earth.getPosition();
        earth.setCenter([c[0], c[1] + 0.1]);
    }, 30);
}

function createMarker(gps) {    
    if (is3D) {
        logCity(gps, function (title) {
            create3DMarker(gps, title);
        });

    } else {
        logCity(gps, function (title) {
            create2DMarker(gps, title);
        });

    }

}

function create2DMarker(gps) {
    var myIcon = L.divIcon({className: 'firework colors0', html: '<span class="fi first"></span><span class="fi fi1"></span><span class="fi fi2"></span><span class="fi fi3"></span>'});
    var marker = L.marker(gps, {icon: myIcon});
    marker.addTo(map2D);
    /*
     setTimeout(function () {
     marker.enabled = false;
     }, 5000);*/
}

function create3DMarker(gps, title) {
    var marker = WE.marker(gps).addTo(earth);
    //marker.bindPopup("<b>Your</b><br>are here!", {maxWidth: 150, closeButton: true});
    var $div = $(marker.element);
    earth.on("mousemove", function (e) {
        // reScaleMarkers($div);
    });
    var div = $('<div class="firework colors0"><h1>' + title + '</h1><span class="fi first"></span><span class="fi fi1"></span><span class="fi fi2"></span><span class="fi fi3"></span></div>');
    $div.append(div);
    setTimeout(function () {
        // reScaleMarkers($div);
    }, 250);

    setTimeout(function () {
        //marker.enabled = false;
        div.addClass("notrans");
    }, 5000);
}

function reScaleMarkers($div) {
    var w = 640;
    var r1 = (90 * ($div.position().left) / (w / 2)) - 90;
    var r2 = (90 * ($div.position().top) / (w / 2)) - 90;
    $div.css({rotateY: r1 + 'deg'});
    $div.css({rotateX: -r2 + 'deg'});
}

function logCity(gps, callback) {
    if (googleTitle) {
        $.getJSON("https://maps.googleapis.com/maps/api/geocode/json?latlng=" + gps[0] + "," + gps[1] + "&key=" + GOOGLE_API_KEY + "&result_type=country|locality",
                function (r) {
                    if (r.status === "OK") {
                        var title = r.results[0].formatted_address;
                        callback(title);
                        log.prepend($("<span>" + title + " slavi s Designuj! s.r.o.</span>"));
                    } else {
                        console.log(r.status);
                    }
                }
        );
    } else {
        callback("");
    }
}

function connectMarkerServer() {
    var items = [];
    // http://www.designuj.cz/dev/3dearth/v6/app/synchro/server.php
    evtSource = new EventSource("/app/synchro/server.php");
    console.log("SSE connection started");
    
    evtSource.addEventListener("msg", function (e) {                
        var obj = JSON.parse(e.data);                                 
        $.each(obj.data, function(){            
            if (!items[$(this)[0].time]){
                items[$(this)[0].time] = 1;
                console.log($(this)[0].x + ", " + $(this)[0].y);
                 createMarker([$(this)[0].x, $(this)[0].y]);
                
                //createFirework($(this)[0]);
                //console.log($(this));
               // log.prepend($(this)[0].time + "<br />");
            }
            
            
        });                                    
    }, false);

    evtSource.onerror = function (e) {
        if (e.readyState === EventSource.CLOSED) {
            console.log("connection closed");
        } else {
            console.log(e);
        }
               
        evtSource.close();

    };

    $(window).unload(function () {        
        evtSource.close();
    });
    
}

function runRandomMarker() {
    markerinterval = setInterval(function () {
        createMarker([Math.random() * 170 - 85, Math.random() * 360 - 180]);
    }, 150);
}

function darker(callback) {
    $("#darker").show().animate({"opacity": 0.8}, 400, callback);
}

function undarker() {
    $("#darker").animate({"opacity": 0}, 400, function () {
        $(this).hide();
    });
}

$(function () {
    initialize();
});