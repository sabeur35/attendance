@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4>My Registered Devices</h4>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <p><strong>Important:</strong> You must register your devices to use them for attendance check-in. 
                        Only registered devices will be accepted for attendance verification.</p>
                    </div>
                    
                    <div class="table-responsive mb-4">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Device Name</th>
                                    <th>MAC Address</th>
                                    <th>Verified</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($devices as $device)
                                <tr>
                                    <td>{{ $device->device_name }}</td>
                                    <td><small>{{ $device->mac_address }}</small></td>
                                    <td>
                                        @if($device->is_verified)
                                            <span class="badge bg-success">Verified</span>
                                        @else
                                            <span class="badge bg-warning text-dark">Pending</span>
                                        @endif
                                    </td>
                                    <td>
                                        <form action="{{ route('devices.destroy', $device) }}" method="POST" 
                                              onsubmit="return confirm('Are you sure you want to remove this device?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">Remove</button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center">No devices registered yet</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="card">
                        <div class="card-header bg-secondary text-white">
                            <h5>Register New Device</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('devices.store') }}" method="POST">
                                @csrf
                                
                                <div class="mb-3">
                                    <label for="device_name" class="form-label">Device Name</label>
                                    <input type="text" class="form-control @error('device_name') is-invalid @enderror" 
                                           id="device_name" name="device_name" required>
                                    <div class="form-text">Enter a name to identify this device (e.g. "My Laptop", "iPhone 13")</div>
                                    @error('device_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="mb-3">
                                    <label for="mac_address" class="form-label">Device ID</label>
                                    <input type="text" class="form-control @error('mac_address') is-invalid @enderror" 
                                           id="mac_address" name="mac_address" readonly>
                                    @error('mac_address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <button type="submit" class="btn btn-primary" id="registerBtn" disabled>Register Device</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Function to generate a device fingerprint
        function getDeviceFingerprint() {
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
        document.getElementById('registerBtn').disabled = false;
    });
</script>
@endsection
