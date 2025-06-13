<div>
    <div class="container mx-auto max-w-sm">
        <div class="bg-white p-6 rounded-lg shadow-lg">
            <div class="grid grid-cols-1 gap-6 mb-6">
                <div>
                    <h2 class="text-2xl font-bold mb-2">Informasi Pegawai</h2>
                    <div class="bg-gray-100 p-4 rounded-lg">
                        <p><strong>Nama Pegawai: </strong> {{ $schedule->user->name }}</p>
                        <p><strong>Kantor: </strong> {{ $schedule->office->name }}</p>
                        <p><strong>Shift: </strong> {{ $schedule->shift->name }} ({{ $schedule->shift->start_time }} - {{ $schedule->shift->end_time }})</p>
                        @if ($schedule->is_wfa)
                            <p class="text-green-500"><strong>Status: </strong> WFA</p>
                        @else
                            <p><strong>Status: </strong> WFO</p>
                        @endif
                    </div>
                </div>
                <div class="grid grid-1 md:grid-cols-2 gap-4">
                    <div class="bg-gray-100 p-4 rounded">
                        <h4 class="text-l font-bold mb-2">Jam Masuk</h4>
                        <p><strong>{{ $attendance->start_time ?? '-' }}</strong></p>
                    </div>
                    <div class="bg-gray-100 p-4 rounded">
                        <h4 class="text-l font-bold mb-2">Jam Keluar</h4>
                        <p><strong>{{ $attendance->end_time ?? '-' }}</strong></p>
                    </div> 
                </div>

                <div>
                    <h2 class="text-2xl font-bold mb-2">Presensi</h2>
                    <div id="map" class="mb-4 border border-gray-300 rounded" wire:ignore></div>
                    <form class="flex justify-between" wire:submit='store' enctype="multipart/form-data">
                        <button type="button" onclick="tagLocation()" class="px-4 py-2 bg-blue-500 text-white rounded cursor-pointer hover:bg-blue-400 transition">Tag Location</button>
                        @if ($insideRadius)                     
                            <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded cursor-pointer hover:bg-green-400 transition">Submit Presensi</button>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    let map;
    let lat;
    let lng;
    let marker;
    const office = [{{ $schedule->office->latitude }}, {{ $schedule->office->longitude }}];
    const radius = {{ $schedule->office->radius }};
    let component;
    const isWfa = @json($schedule->is_wfa); 

    document.addEventListener('livewire:initialized', function () {
        component = @this;
        map = L.map('map').setView([{{ $schedule->office->latitude }}, {{ $schedule->office->longitude }}], 15);
        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
        }).addTo(map);

        var circle = L.circle(office, {
            color: 'red',
            fillColor: '#f03',
            fillOpacity: 0.5,
            radius: radius
        }).addTo(map);
    })

    function tagLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                lat = position.coords.latitude;
                lng = position.coords.longitude;

                console.log('Latitude: ' + lat);
                console.log('Longitude: ' + lng);
                
                if (marker) {
                    map.removeLayer(marker);
                }

                marker = L.marker([lat, lng]).addTo(map);
                map.setView([lat, lng], 18);

                if (isWithinRadius(lat, lng, office, radius)) {
                    component.set('insideRadius', true);
                    component.set('latitude', lat);
                    component.set('longitude', lng);
                } else {
                    if (isWfa) {
                        component.set('insideRadius', true);
                        component.set('latitude', lat);
                        component.set('longitude', lng);
                        alert('Anda sedang WFA!')
                    } else {
                        alert('Anda berada di luar radius!')
                    }
                }
            })
        } else {
            alert('Tidak bisa tag location!')
        }
    }

    function isWithinRadius(lat, lng, center, radius){
        let distance = map.distance([lat, lng], center);
        return distance <= radius;
    }
</script>