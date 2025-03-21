<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>DTDC</title>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <link rel="stylesheet" href="{{ asset('assets/plugins/custom/leaflet/leaflet.bundle.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/plugins/global/plugins.bundle.css') }}" />
    <script src="{{ asset('assets/plugins/global/plugins.bundle.js') }}"></script>
    <script src="{{ asset('assets/plugins/custom/leaflet/leaflet.bundle.js') }}"></script>

    <style>
        html,
        body {
            margin: 0;
            padding: 0;
        }

        #map-legend {
            position: absolute;
            bottom: 20px;
            right: 20px;
            background-color: white;
            padding: 10px;
            border-radius: 5px;
            box-shadow: 0px 0px 5px rgba(0, 0, 0, 0.3);
            font-size: 14px;
            z-index: 1000;
            /* Make sure the legend stays on top of the map */
        }

        #map-legend h4 {
            margin: 0 0 5px 0;
            font-size: 14px;
            font-weight: bold;
        }

        #map-legend p {
            margin: 5px 0;
            font-size: 12px;
            line-height: 16px;
        }

        .legend-color {
            display: inline-block;
            width: 15px;
            height: 15px;
            margin-right: 5px;
            vertical-align: middle;
            border: 1px solid #999;
        }

        .leaflet-popup-content {
            font-size: 14px;
            line-height: 1.5;
        }

        .leaflet-tooltip {
            font-size: 12px;
            font-weight: bold;
            background-color: rgba(255, 255, 255, 0.8);
            border: 1px solid #ccc;
            padding: 4px;
            border-radius: 4px;
        }

        .filter-container {
            margin: 10px;
        }

        .filter-container input[type="checkbox"] {
            margin-right: 5px;
        }

        .filter-label {
            font-size: 14px;
            font-weight: bold;
            color: #333;
            margin-right: 15px;
        }
    </style>

</head>

<body>


    <div id="map" style="width: 100%; height: 100vh;"></div>

    <div id="map-legend">
        <h4>Informasi Map</h4>
        <div id="filters">
            <input type="checkbox" id="green" checked> <span class="legend-color"
                style="background-color: green;"></span> Tertarik dengan produk<br>
            <input type="checkbox" id="yellow" checked> <span class="legend-color"
                style="background-color: yellow;"></span> Tidak tertarik<br>
            <input type="checkbox" id="red" checked> <span class="legend-color"
                style="background-color: red;"></span> Hanya taruh brosur<br>
        </div>
    </div>
    <script src="https://unpkg.com/leaflet.markercluster@1.4.1/dist/leaflet.markercluster.js"></script>
    <script>
        var redMarker = L.icon({
            iconUrl: '{{ asset('assets/plugins/custom/leaflet/images/leaflet/marker-icon-red.png') }}',
            iconSize: [23, 35],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34],
            shadowSize: [41, 41],
            shadowAnchor: [12, 41]
        });

        var greenMarker = L.icon({
            iconUrl: '{{ asset('assets/plugins/custom/leaflet/images/leaflet/marker-icon-green.png') }}',
            iconSize: [23, 35],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34],
            shadowSize: [41, 41],
            shadowAnchor: [12, 41]
        });

        var yellowMarker = L.icon({
            iconUrl: '{{ asset('assets/plugins/custom/leaflet/images/leaflet/marker-icon-yellow.png') }}',
            iconSize: [23, 35],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34],
            shadowSize: [41, 41],
            shadowAnchor: [12, 41]
        });

        function getColor(status) {
            switch (status) {
                case 'Tertarik dengan produk':
                    return greenMarker;
                case 'Hanya taruh brosur':
                    return redMarker;
                case 'Tidak tertarik':
                    return yellowMarker;
            }
        }

        function getTag(status) {
            switch (status) {
                case 'Tertarik dengan produk':
                    return 'Tertarik dengan produk';
                case 'Hanya taruh brosur':
                    return 'Hanya taruh brosur';
                case 'Tidak tertarik':
                    return 'Tidak tertarik';
            }
        }

        $(document).ready(function() {
            var map = L.map('map').setView([-1.2495105, 116.8749959], 14);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);

            // Initialize marker cluster groups for each marker type
            var redCluster = L.markerClusterGroup({
                iconCreateFunction: function(cluster) {
                    return L.divIcon({
                        html: '<div style="background-color:rgba(255,0,0,0.6);width:40px;height:40px;border-radius:50%;line-height:40px;text-align:center;color:white;">' +
                            cluster.getChildCount() + '</div>',
                        className: 'custom-cluster',
                        iconSize: [40, 40]
                    });
                }
            });

            var greenCluster = L.markerClusterGroup({
                iconCreateFunction: function(cluster) {
                    return L.divIcon({
                        html: '<div style="background-color:rgba(0,255,0,0.6);width:40px;height:40px;border-radius:50%;line-height:40px;text-align:center;color:white;">' +
                            cluster.getChildCount() + '</div>',
                        className: 'custom-cluster',
                        iconSize: [40, 40]
                    });
                }
            });
            var yellowCluster = L.markerClusterGroup({
                iconCreateFunction: function(cluster) {
                    return L.divIcon({
                        html: '<div style="background-color:rgba(255,255,0,0.6);width:40px;height:40px;border-radius:50%;line-height:40px;text-align:center;color:white;">' +
                            cluster.getChildCount() + '</div>',
                        className: 'custom-cluster',
                        iconSize: [40, 40]
                    });
                }
            });

            @foreach ($volunteers as $volunteer)
                @php
                    $houseNumber = $volunteer->house_number;
                    if (strpos($houseNumber, '\\') !== false) {
                        $houseNumber = str_replace('\\', '\\\\', $houseNumber);
                    }
                @endphp
                var status = '{{ $volunteer->status }}';
                var marker = L.marker([{{ $volunteer->latitude }}, {{ $volunteer->longitude }}], {
                    icon: getColor(status)
                }).bindPopup(
                    "<b>RT {{ $volunteer->rt }}, No. {{ $houseNumber }}</b><br>Nama: {{ $volunteer->name ?? '-' }}"
                ).bindTooltip(
                    "{{ $volunteer->name ?? 'Unknown' }}", {
                        permanent: false
                    }
                );

                // Add markers to corresponding clusters based on status
                switch (status) {
                    case 'Tertarik dengan produk':
                        greenCluster.addLayer(marker);
                        break;
                    case 'Hanya taruh brosur':
                        redCluster.addLayer(marker);
                        break;
                    case 'Tidak tertarik':
                        yellowCluster.addLayer(marker);
                        break;
                }
            @endforeach

            // Add clusters to map
            map.addLayer(redCluster);
            map.addLayer(greenCluster);
            map.addLayer(yellowCluster);

            // Function to update markers based on checkboxes
            function updateMarkers() {
                if ($('#red').is(':checked')) map.addLayer(redCluster);
                else map.removeLayer(redCluster);
                if ($('#green').is(':checked')) map.addLayer(greenCluster);
                else map.removeLayer(greenCluster);
                if ($('#yellow').is(':checked')) map.addLayer(yellowCluster);
                else map.removeLayer(yellowCluster);
            }

            // Bind change events to checkboxes
            $('#filters input[type="checkbox"]').change(function() {
                updateMarkers();
            });

            // Initial update to set markers visibility
            updateMarkers();
        });
    </script>
</body>

</html>
