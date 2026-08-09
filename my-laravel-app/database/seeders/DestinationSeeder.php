<?php

namespace Database\Seeders;

use App\Models\Destination;
use App\Models\Town;
use Illuminate\Database\Seeder;

class DestinationSeeder extends Seeder
{
    public function run(): void
    {
        $laoag = Town::where('slug', 'laoag-city')->first();
        $paoay = Town::where('slug', 'paoay')->first();

        if ($laoag) {
            $laoagDestinations = [
                [
                    'name' => "St. William's Cathedral",
                    'type' => 'historical',
                    'description' => 'A iconic Italian Renaissance church built by Augustinian friars in 1612.',
                    'image' => '/assets/images/Laoag.jpg',
                    'history' => 'Constructed in 1612, St. William Cathedral is renowned for its Italian Renaissance architecture and its sinking bell tower located 85 meters away.',
                    'significance' => 'Serves as the seat of the Roman Catholic Diocese of Laoag.',
                    'coordinates' => '18.1963° N, 120.5927° E',
                    'order' => 1,
                ],
                [
                    'name' => 'Sinking Bell Tower',
                    'type' => 'historical',
                    'description' => 'One of the tallest bell towers in the Philippines, sinking by an estimated 1 inch per year.',
                    'image' => '/assets/images/Laoag.jpg',
                    'history' => 'Built in 1612 on sandy foundation soil, its immense weight causes it to sink continuously.',
                    'significance' => 'Historical landmark and popular tourist attraction in central Laoag.',
                    'coordinates' => '18.1968° N, 120.5929° E',
                    'order' => 2,
                ],
                [
                    'name' => 'La Paz Sand Dunes',
                    'type' => 'natural',
                    'description' => 'A 85-square-kilometer coastal desert reserve perfect for 4x4 off-roading and sandboarding.',
                    'image' => '/assets/images/Laoag.jpg',
                    'history' => 'Formed over centuries by coastal winds and ocean tides, declared a National Geological Monument.',
                    'significance' => 'Famous filming location for classic films like Panday and Mad Max style stunts.',
                    'coordinates' => '18.2045° N, 120.5512° E',
                    'order' => 3,
                ],
                [
                    'name' => 'Museo Ilocos Norte',
                    'type' => 'cultural',
                    'description' => 'Housed in a historical brick Tabacalera warehouse, showcases Ilocano heritage and ethnography.',
                    'image' => '/assets/images/Laoag.jpg',
                    'history' => 'Originally built as a tobacco warehouse during the Spanish monopoly era.',
                    'significance' => 'Preserves indigenous and colonial artifacts of the province.',
                    'coordinates' => '18.1955° N, 120.5915° E',
                    'order' => 4,
                ],
            ];

            foreach ($laoagDestinations as $dest) {
                Destination::updateOrCreate(
                    ['town_id' => $laoag->id, 'name' => $dest['name']],
                    $dest
                );
            }
        }

        if ($paoay) {
            $paoayDestinations = [
                [
                    'name' => 'Paoay Church (Saint Augustine Church)',
                    'type' => 'historical',
                    'description' => 'UNESCO World Heritage site famous for Earthquake Baroque architecture and massive coral brick buttresses.',
                    'image' => '/assets/images/Paoay.jpg',
                    'history' => 'Completed in 1710 after decades of construction using coral stones and sugar cane mortar mixtures.',
                    'significance' => 'One of four Baroque Churches of the Philippines inscribed as a UNESCO World Heritage Site.',
                    'coordinates' => '18.0617° N, 120.5218° E',
                    'order' => 1,
                ],
                [
                    'name' => 'Paoay Lake National Park',
                    'type' => 'natural',
                    'description' => 'A 470-hectare freshwater lake rich in local legends and migratory bird life.',
                    'image' => '/assets/images/Paoay.jpg',
                    'history' => 'Legend states the lake submerged a wealthy village whose inhabitants were punished for vanity.',
                    'significance' => 'Protected national park and important ecological sanctuary.',
                    'coordinates' => '18.1065° N, 120.5385° E',
                    'order' => 2,
                ],
                [
                    'name' => 'Malacañang of the North',
                    'type' => 'cultural',
                    'description' => 'A majestic two-story mansion overlooking Paoay Lake, serving as a presidential museum.',
                    'image' => '/assets/images/Paoay.jpg',
                    'history' => 'Built as the official residence for President Ferdinand E. Marcos during his administration.',
                    'significance' => 'Features traditional Bahay na Bato architectural elements with grand wooden verandahs.',
                    'coordinates' => '18.1042° N, 120.5348° E',
                    'order' => 3,
                ],
                [
                    'name' => 'Paoay Sand Dunes (Suba)',
                    'type' => 'ecotourism',
                    'description' => 'Rolling desert dunes offering 4x4 thrill rides and sunset viewing spots.',
                    'image' => '/assets/images/Paoay.jpg',
                    'history' => 'Part of the extensive Ilocos Norte sand dune ecosystem along the West Philippine Sea.',
                    'significance' => 'High-energy adventure hub for international and local tourists.',
                    'coordinates' => '18.0833° N, 120.4950° E',
                    'order' => 4,
                ],
            ];

            foreach ($paoayDestinations as $dest) {
                Destination::updateOrCreate(
                    ['town_id' => $paoay->id, 'name' => $dest['name']],
                    $dest
                );
            }
        }
    }
}
