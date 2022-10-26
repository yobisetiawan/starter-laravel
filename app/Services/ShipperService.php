<?php

namespace App\Services;

use App\Models\Base\Address;
use App\Models\Ecommerce\Order;
use Illuminate\Support\Facades\Log;

class ShipperService
{

    protected $apiKey;
    protected $baseUrl;
    protected $client;

    public function __construct()
    {
        $this->apiKey = env("SHIPPER_API_KEY");
        $this->baseUrl = env("SHIPPER_BASE_URL");
        $this->client = new \GuzzleHttp\Client();
    }

    public function getArea($q)
    {
        return $this->exSave(function () use ($q) {

            if (strlen($q) <= 2) {
                return [];
            }

            $ress = $this->client->request('GET', $this->baseUrl . '/location?keyword=' . $q, [
                'headers' => [
                    'Accept' => 'application/json',
                    'X-API-Key' =>  $this->apiKey,
                ],
            ]);

            return json_decode($ress->getBody(), true);
        });
    }


    /**
     * Example createOrder Shipper
     *
     *      $dt_shipper_order = [
     *            'consignee' => [
     *              'name' => "consignee name",
     *              "phone_number" => "62xxx",
     *         ],
     *        'consigner' => [
     *              'name' => "consigner name",
     *             "phone_number" => "62xxx",
     *         ],
     *         "courier" => ["rate_id" => 2],
     *        "destination" => [
     *            "address" => "Jalan kenanga",
     *              "area_id" => 12212
     *           ],
     *          "origin" => [
     *              "address" => "Jalan kenanga",
     *             "area_id" => 12623
     *          ],
     *           "package" => [
     *              "items" => [
     *                   [
     *                      "name" => "Baju",
     *                      "price" => 120000,
     *                     "qty" => 1
     *                ]
     *              ],
     *              "height" => 60,
     *             "price" => 120000,
     *            "weight" => 2,
     *             "width" => 30,
     *              "length" => 30,
     *             "package_type" => 2
     *          ],
     *          "coverage" => "domestic",
     *          "payment_type" => "cash"
     *     ];
     *
     */
    public function createOrder($body)
    {
        return $this->exSave(function () use ($body) {
            Log::info('Shipper Create Order');
            Log::info(json_encode($body));
            $ress = $this->client->request('POST', $this->baseUrl . '/order', [
                'body' =>  json_encode($body),
                'headers' => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                    'X-API-Key' =>  $this->apiKey,
                ],
            ]);
            return json_decode($ress->getBody(), true);
        });
    }

    /**
     * @param mixed $items (Object ORDER)
     * @param mixed $bodyShipperDomestic (Body Pricing Domestic For Dimensions array)
     *
     * @return mixed array
     */

    public function bodyCreateOrder(Order $data, $bodyShipperDomestic, $rate_id, $insurance = false)
    {
        $items = $data->orderItems;

        $product = [];
        foreach ($items as $key => $value) {
            if (($value->stock->sell_price ?? 0) > 0) {
                $product[$key]['name'] = (string) $value->stock->item->title;
                $product[$key]['price'] = $value->stock->sell_price;
                $product[$key]['qty'] = (int) $value->quantity;
            }
        }

        return [
            'consignee' => [
                'name' => $data->address['customer']['recipient_name'],
                'phone_number' => $data->address['customer']['phone'],
            ],
            'consigner' => [
                'name' => $data->seller->description,
                'phone_number' => $data->address['seller']['phone'],
            ],
            'courier' => [
                'rate_id' => $rate_id,
                'cod' => false,
                'use_insurance' => (bool) $insurance
            ],
            'destination' => [
                'address' => (string) $data->address['customer']['address'],
                'area_id' => $data->address['customer']['area_id'],
                'lat' => (string) $data->address['customer']['map_lat'],
                'lng' => (string) $data->address['customer']['map_lon'],
            ],
            'origin' => [
                'address' => $data->address['seller']['address'],
                'area_id' => $data->address['seller']['area_id'],
                'lat' => (string) $data->address['seller']['map_lat'],
                'lng' => (string) $data->address['seller']['map_lon'],
            ],
            'package' => [
                'items' => $product,
                'height' => $bodyShipperDomestic['height'],
                'price' => $bodyShipperDomestic['item_value'],
                'weight' => $bodyShipperDomestic['weight'],
                'width' => $bodyShipperDomestic['width'],
                'length' => $bodyShipperDomestic['length'],
                'package_type' => 2
            ],
            "coverage" => "domestic",
            "payment_type" => "cash",
            "best_prices" => false,
            "external_id" => $data->uuid
        ];
    }

    /**
     * Example getPricingDomestic $data get prcing
     * $body = [
     *    'destination' => [
     *        'area_id' => 12623 // from shipper api
     *    ],
     *    'origin' => [
     *        'area_id' => 12212 // from shipper api
     *   ],
     *    'height' => 10, //cm
     *   "width" => 20, //cm
     *    "length" => 10,
     *   "item_value" => 100000, //IDR
     *    "weight" => 2,
     *    "for_order" => true
     * ];
     * rate_type = regular, express, trucking, same-day, instant."
     */
    public function getPricingDomestic($body, $rate_type = null)
    {
        return $this->exSave(function () use ($body, $rate_type) {
            Log::info('Shipper prcing domestic');
            Log::info(json_encode($body));
            $ress = $this->client->request('POST', $this->baseUrl . '/pricing/domestic/' . $rate_type, [
                'body' => json_encode($body),
                'headers' => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                    'X-API-Key' =>  $this->apiKey,
                ],
            ]);

            return json_decode($ress->getBody(), true);
        });
    }

    /**
     *
     * @param mixed $items (cart_items models)
     *
     * Area ID From Shipper
     *
     * @param mixed $destinition_area_id (destinition Area ID)
     * @param mixed $origin_area_id (Origin Area ID)
     *
     * @return mixed example: [
     *    'destination' => [
     *        'area_id' => 12623
     *    ],
     *    'origin' => [
     *        'area_id' => 12212
     *   ],
     *    'height' => 10, //cm
     *   "width" => 20, //cm
     *    "length" => 10,
     *   "item_value" => 100000, //IDR
     *    "weight" => 2,
     *    "for_order" => true
     * ]
     */

    public function bodyPricingDomestic($items, Address $destinition, Address $origin)
    {
        $item_value = 0;
        $we = 0;
        $vol = 0;

        foreach ($items as $value) {
            $we += ($value->item->data['product_weight'] * $value->quantity);
            $item_value += ($value->stock->sell_price * $value->quantity);

            $volume = ($value->item->data['product_height'] *
                $value->item->data['product_width'] *
                $value->item->data['product_length']) *
                (int)$value->quantity;

            $vol = $volume + $vol;
        }
        // $vol = $vol / 10;
        $fd = $vol ** (1 / 3);

        $dimension = [
            "height" => (int) ceil($fd),
            "width" => (int) ceil($fd),
            "length" => (int) ceil($fd),
        ];

        return [
            'destination' => [
                'area_id' => $destinition->area_id,
                'lat' => "$destinition->map_lat",
                'lng' => "$destinition->map_lon",
            ],
            'origin' => [
                'area_id' => $origin->area_id,
                'lat' => "$origin->map_lat",
                'lng' => "$origin->map_lon",
            ],
            'height' => $dimension['height'],
            "width" => $dimension['width'],
            "length" => $dimension['length'],
            "item_value" => $item_value,
            "weight" => (int) ceil($we / 1000),
            "for_order" => true,
            "sort_by" => [
                "final_price"
            ],
        ];
    }





    public function order($order_id)
    {
        return $this->exSave(function () use ($order_id) {
            $ress = $this->client->request('GET', $this->baseUrl . '/order/' . $order_id, [
                'headers' => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                    'X-API-Key' =>  $this->apiKey,
                ],
            ]);

            return json_decode($ress->getBody(), true);
        });
    }

    public function locationCountries()
    {
        return $this->exSave(function () {
            $params = http_build_query(request()->all());
            $ress = $this->client->request('GET', $this->baseUrl . '/location/countries?' . $params, [
                'headers' => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                    'X-API-Key' =>  $this->apiKey,
                ],
            ]);

            return json_decode($ress->getBody(), true);
        });
    }

    public function locationProvinces($country_id)
    {
        return $this->exSave(function () use ($country_id) {
            $params = http_build_query(request()->all());
            $ress = $this->client->request('GET', $this->baseUrl . '/location/country/' . $country_id . '/provinces?' . $params, [
                'headers' => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                    'X-API-Key' =>  $this->apiKey,
                ],
            ]);

            return json_decode($ress->getBody(), true);
        });
    }

    public function locationCities($province_id)
    {
        return $this->exSave(function () use ($province_id) {
            $params = http_build_query(request()->all());
            $ress = $this->client->request('GET', $this->baseUrl . '/location/province/' . $province_id . '/cities?' . $params, [
                'headers' => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                    'X-API-Key' =>  $this->apiKey,
                ],
            ]);

            return json_decode($ress->getBody(), true);
        });
    }

    public function locationSuburbs($city_id)
    {
        return $this->exSave(function () use ($city_id) {
            $params = http_build_query(request()->all());
            $ress = $this->client->request('GET', $this->baseUrl . '/location/city/' . $city_id . '/suburbs?' . $params, [
                'headers' => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                    'X-API-Key' =>  $this->apiKey,
                ],
            ]);

            return json_decode($ress->getBody(), true);
        });
    }

    public function locationAreas($suburb_id)
    {
        return $this->exSave(function () use ($suburb_id) {
            $params = http_build_query(request()->all());
            $ress = $this->client->request('GET', $this->baseUrl . '/location/suburb/' . $suburb_id . '/areas?' . $params, [
                'headers' => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                    'X-API-Key' =>  $this->apiKey,
                ],
            ]);

            return json_decode($ress->getBody(), true);
        });
    }

    public function timeslot()
    {
        return $this->exSave(function () {
            $params = http_build_query(request()->all());
            $ress = $this->client->request('GET', $this->baseUrl . '/pickup/timeslot?' . $params, [
                'headers' => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                    'X-API-Key' =>  $this->apiKey,
                ],
            ]);

            return json_decode($ress->getBody(), true);
        });
    }

    /**
     *  '{
     *"data": {
     *       "order_activation": {
     *           "order_id": [ "215GRRV8Y55VQ" ],
     *           "timezone" : "Asia/Jakarta",
     *           "start_time": "2021-09-09T15:00:00+07:00",
     *           "end_time": "2021-09-09T17:59:00+07:00"
     *       }
     *   }
     *}'
     */
    public function pickupWithTimeSlot($shipper_order_id, $time_zone, $start_time, $end_time)
    {
        return $this->exSave(function () use ($shipper_order_id, $time_zone, $start_time, $end_time) {
            $body = [
                'data' => [
                    "order_activation" => [
                        "order_id" => [$shipper_order_id],
                        "timezone" =>  $time_zone,
                        "start_time" => $start_time,
                        "end_time" => $end_time,
                    ],
                ]
            ];

            Log::info('Shipper pickup with time slot');
            Log::info(json_encode($body));
            $ress = $this->client->request('POST', $this->baseUrl . '/pickup/timeslot', [
                'body' => json_encode($body),
                'headers' => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                    'X-API-Key' =>  $this->apiKey,
                ],
            ]);


            return json_decode($ress->getBody(), true);
        });
    }

    public function cancelPickupWithTimeSlot($pickup_code)
    {
        return $this->exSave(function () use ($pickup_code) {
            $body = [
                'pickup_Code' => $pickup_code
            ];


            $ress = $this->client->request('PATCH', $this->baseUrl . '/pickup/cancel', [
                'body' => json_encode($body),
                'headers' => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                    'X-API-Key' =>  $this->apiKey,
                ],
            ]);


            return json_decode($ress->getBody(), true);
        });
    }

    private function exSave($func)
    {
        try {
            return $func();
        } catch (\GuzzleHttp\Exception\RequestException $th) {
            $dd = $th->getResponse()->getBody()->getContents();
            $msg = json_decode($dd, true)['metadata']['errors'][0]['message'] ?? null;
            abort($th->getResponse()->getStatusCode(), ($msg ? 'Shipper: ' . $msg : null)  ?? $dd);
        } catch (\Throwable $th) {
            $code = $th->getCode();
            $msg = $th->getMessage();
            if ($code == 404) {
                if (empty($msg)) {
                    abort($code, 'Shipper: Data Not Found');
                }
                abort($code, 'Shipper: ' . $msg);
            } else {
                abort(!empty($code) ? $code : 500, 'Shipper: ' . $msg);
            }
        }
    }
}
