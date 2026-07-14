<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Hotel;
use App\Models\HotelRoomInventory;
use Illuminate\Support\Carbon;

$hotel = Hotel::find(1);
if (! $hotel) {
    echo "Hotel not found\n";
    exit(0);
}

echo "Hotel: {$hotel->hotel_name}\n";
foreach ($hotel->roomTypes as $rt) {
    echo "RoomType: {$rt->id} {$rt->room_name} total_rooms={$rt->total_rooms} available_rooms={$rt->available_rooms}\n";
    $inv = HotelRoomInventory::where('hotel_id', $hotel->id)
        ->where('hotel_room_type_id', $rt->id)
        ->orderBy('inventory_date')
        ->get();
    foreach ($inv as $row) {
        echo $row->inventory_date->format('Y-m-d') . " total=" . $row->total_rooms . " avail=" . $row->available_rooms . " booked=" . $row->booked_rooms . " status=" . $row->status . "\n";
    }
    $ci = Carbon::parse('2027-07-22')->startOfDay();
    $co = Carbon::parse('2027-07-30')->startOfDay();
    $sum = HotelRoomInventory::summarizeAvailability($hotel->id, $rt->id, $ci, $co);
    echo "Summary: available=" . $sum['available_rooms'] . " status=" . $sum['status'] . "\n\n";
}
