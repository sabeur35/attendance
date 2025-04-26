@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4>Check-in to Class Session</h4>
                </div>
                <div class="card-body">
                    <h5>Course: {{ $session->course->name }} ({{ $session->course->code }})</h5>
                    <p>Session Time: {{ $session->start_time->format('M d, Y - h:i A') }} to {{ $session->end_time->format('h:i A') }}</p>
                    
                    <form id="attendanceForm" action="{{ route('sessions.attend.store', $session) }}" method="POST">
                        @csrf
                        <input type="hidden" name="mac_address" id="mac_address">
                        <input type="hidden" name="latitude" id="latitude">
                        <input type="hidden" name="longitude" id="longitude">
                        
                        <div class="mb-3" id="locationStatus">
                            <div class="alert alert-info">
                                Please allow location access to check in...
                            </div>
                        </div>
                        
                        <div class="mb-3" id="macStatus">
                            <div class="alert alert-info">
                                Getting device information...
                            </div>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-success btn-lg" id="checkInBtn" disabled>
                                Check In
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        let locationReady = false;
        let macReady = false;
        
        // Get location
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                function(position) {
                    document.getElementById('latitude').value = position.coords.latitude;
                    document.getElementById('longitude').value = position.coords.longitude;
                    document.getElementById('locationStatus').innerHTML = 
                        '<div class="alert alert-success">Location captured successfully!</div>';
                    locationReady = true;
                    checkReadiness();
                },
                function(error) {
                    document.getElementById('locationStatus').innerHTML = 
                        '<div class="alert alert-danger">Error getting location: ' + error.message + '</div>';
                }
            );
        } else {
            document.getElementById('locationStatus').innerHTML = 
                '<div class="alert alert-danger">Geolocation is not supported by this browser.</div>';
        }
        
        // Get MAC address using a simple client-side solution
        // Note: In a real app, you'd want to use a more sophisticated approach
        // as getting the actual MAC is restricted in browsers for privacy reasons
        function getDeviceFingerprint() {
            // This is a simplified approach - in production, use a library like Fingerprint.js
            const userAgent = navigator.userAgent;
            const screenPrint = screen.width + 'x' + screen.height + 'x' + screen.colorDepth;
            const timezoneOffset = new Date().getTimezoneOffset();
            const lang = navigator.language;
            const platform = navigator.platform;
            
            // Create a hash of these values to use as a device identifier
            let deviceId = btoa(userAgent + screenPrint + timezoneOffset + lang + platform);
            // Trim to a reasonable length and format to look like a MAC
            deviceId = deviceId.replace(/[^a-zA-Z0-9]/g, '').substr(0, 12);
            
            // Format like a MAC address
            return deviceId.match(/.{1,2}/g).join(':').toUpperCase();
        }
        
        const macAddress = getDeviceFingerprint();
        document.getElementById('mac_address').value = macAddress;
        document.getElementById('macStatus').innerHTML = 
            '<div class="alert alert-success">Device ID captured: ' + macAddress + '</div>';
        macReady = true;
        checkReadiness();
        
        function checkReadiness() {
            if (locationReady && macReady) {
                document.getElementById('checkInBtn').disabled = false;
            }
        }
    });
</script>
@endsection
