@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4>Create New Class Session</h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('courses.sessions.store', $course) }}">
                        @csrf

                        <div class="mb-3">
                            <label for="start_time" class="form-label">Start Time</label>
                            <input type="datetime-local" class="form-control @error('start_time') is-invalid @enderror"
                                id="start_time" name="start_time" value="{{ old('start_time') }}" required>
                            @error('start_time')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="end_time" class="form-label">End Time</label>
                            <input type="datetime-local" class="form-control @error('end_time') is-invalid @enderror"
                                id="end_time" name="end_time" value="{{ old('end_time') }}" required>
                            @error('end_time')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Class Location</label>
                            <p class="text-muted small">Click on the map to set the class location or use the current location button.</p>

                            <div id="map" style="height: 300px;" class="mb-2 border rounded"></div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="latitude" class="form-label">Latitude</label>
                                        <input type="text" class="form-control @error('latitude') is-invalid @enderror"
                                            id="latitude" name="latitude" value="{{ old('latitude') }}" required readonly>
                                        @error('latitude')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="longitude" class="form-label">Longitude</label>
                                        <input type="text" class="form-control @error('longitude') is-invalid @enderror"
                                            id="longitude" name="longitude" value="{{ old('longitude') }}" required readonly>
                                        @error('longitude')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="radius_meters" class="form-label">Allowed Radius (meters)</label>
                                <input type="number" class="form-control @error('radius_meters') is-invalid @enderror"
                                    id="radius_meters" name="radius_meters" value="{{ old('radius_meters', 100) }}"
                                    min="10" max="1000" required>
                                <div class="form-text">Students must be within this radius of the class location to check in.</div>
                                @error('radius_meters')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <button type="button" id="useCurrentLocation" class="btn btn-outline-primary mb-3">
                                <i class="bi bi-geo-alt"></i> Use Current Location
                            </button>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">Create Session</button>
                            <a href="{{ route('courses.sessions.index', $course) }}" class="btn btn-outline-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
<style>
    .radius-circle {
        stroke: #3388ff;
        stroke-opacity: 0.8;
        stroke-width: 2;
        fill: #3388ff;
        fill-opacity: 0.2;
    }
</style>
@endsection

@section('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize the map
        var map = L.map('map', {
            zoomControl: true,
            maxZoom: 19
        }).setView([0, 0], 2);

        // Define base maps
        var openStreetMap = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
            name: 'Street'
        });

        var satelliteMap = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
            attribution: 'Tiles &copy; Esri &mdash; Source: Esri, i-cubed, USDA, USGS, AEX, GeoEye, Getmapping, Aerogrid, IGN, IGP, UPR-EGP, and the GIS User Community',
            name: 'Satellite'
        });

        var hybridMap = L.layerGroup([
            satelliteMap,
            L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/Reference/World_Boundaries_and_Places/MapServer/tile/{z}/{y}/{x}', {
                attribution: 'Labels &copy; Esri &mdash; Source: Esri',
                pane: 'shadowPane'
            })
        ]);

        // Add default layer to map
        openStreetMap.addTo(map);

        // Create layer control
        var baseMaps = {
            "Street": openStreetMap,
            "Satellite": satelliteMap,
            "Hybrid": hybridMap
        };

        L.control.layers(baseMaps, null, {position: 'topright'}).addTo(map);

        // Add search control
        L.Control.geocoder({
            defaultMarkGeocode: false,
            position: 'topleft',
            placeholder: 'Search for a place...',
        }).on('markgeocode', function(e) {
            var bbox = e.geocode.bbox;
            var poly = L.polygon([
                bbox.getSouthEast(),
                bbox.getNorthEast(),
                bbox.getNorthWest(),
                bbox.getSouthWest()
            ]);
            map.fitBounds(poly.getBounds());
            setMarkerPosition(e.geocode.center.lat, e.geocode.center.lng);
        }).addTo(map);

        var marker;
        var radiusCircle;

        // Handle map click
        map.on('click', function(e) {
            setMarkerPosition(e.latlng.lat, e.latlng.lng);
        });

        // Handle "Use Current Location" button click
        document.getElementById('useCurrentLocation').addEventListener('click', function() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    setMarkerPosition(position.coords.latitude, position.coords.longitude);
                    map.setView([position.coords.latitude, position.coords.longitude], 15);
                }, function(error) {
                    alert('Error getting your location: ' + error.message);
                });
            } else {
                alert('Geolocation is not supported by this browser.');
            }
        });

        // Update radius circle when radius input changes
        document.getElementById('radius_meters').addEventListener('input', function() {
            updateRadiusCircle();
        });

        function setMarkerPosition(lat, lng) {
            document.getElementById('latitude').value = lat.toFixed(7);
            document.getElementById('longitude').value = lng.toFixed(7);

            if (marker) {
                marker.setLatLng([lat, lng]);
            } else {
                marker = L.marker([lat, lng], {
                    draggable: true
                }).addTo(map);

                marker.on('dragend', function() {
                    var position = marker.getLatLng();
                    setMarkerPosition(position.lat, position.lng);
                });
            }

            // Update popup content
            var popupContent = '<strong>Selected Location</strong><br>' +
                              'Latitude: ' + lat.toFixed(7) + '<br>' +
                              'Longitude: ' + lng.toFixed(7);

            marker.bindPopup(popupContent).openPopup();

            updateRadiusCircle();
        }

        function updateRadiusCircle() {
            var radius = parseInt(document.getElementById('radius_meters').value);
            var lat = parseFloat(document.getElementById('latitude').value);
            var lng = parseFloat(document.getElementById('longitude').value);

            if (!isNaN(radius) && !isNaN(lat) && !isNaN(lng)) {
                if (radiusCircle) {
                    radiusCircle.setLatLng([lat, lng]);
                    radiusCircle.setRadius(radius);
                } else if (marker) {
                    radiusCircle = L.circle([lat, lng], {
                        radius: radius,
                        className: 'radius-circle'
                    }).addTo(map);
                }
            }
        }

        // If there are old values, set them
        var oldLat = "{{ old('latitude') }}";
        var oldLng = "{{ old('longitude') }}";

        if (oldLat && oldLng) {
            setMarkerPosition(parseFloat(oldLat), parseFloat(oldLng));
            map.setView([parseFloat(oldLat), parseFloat(oldLng)], 15);
        } else if (navigator.geolocation) {
            // Try to get user's current location as a starting point
            navigator.geolocation.getCurrentPosition(function(position) {
                setMarkerPosition(position.coords.latitude, position.coords.longitude);
                map.setView([position.coords.latitude, position.coords.longitude], 15);
            }, function() {
                // On error, do nothing - map will stay at default view
            });
        }
    });
</script>
@endsection
