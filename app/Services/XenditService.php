<?php

namespace App\Services;

use App\Models\Finance\Payment;
use Illuminate\Support\Facades\Log;
use Xendit\Platform;
use Xendit\Xendit;

class XenditService
{


    public function __construct()
    {
        Xendit::setApiKey(env('XENDIT_SECRET_API_KEY'));
    }


    /**
     * Create a new payment invoice
     *
     *  $params = [
     *         'external_id' => 'demo_1475801962607',
     *         'amount' => 50000,
     *         'description' => 'Invoice Demo #123',
     *         'invoice_duration' => 86400,
     *         'customer' => [
     *             'given_names' => 'John',
     *            'surname' => 'Doe',
     *           'email' => 'johndoe@example.com',
     *            'mobile_number' => '+6287774441111',
     *           'address' => [
     *                [
     *                     'city' => 'Jakarta Selatan',
     *                     'country' => 'Indonesia',
     *                     'postal_code' => '12345',
     *                     'state' => 'Daerah Khusus Ibukota Jakarta',
     *                    'street_line1' => 'Jalan Makan',
     *                     'street_line2' => 'Kecamatan Kebayoran Baru'
     *                 ]
     *           ]
     *        ],
     *        'customer_notification_preference' => [
     *            'invoice_created' => [
     *                 'whatsapp',
     *                 'sms',
     *                  'email',
     *                'viber'
     *            ],
     *             'invoice_reminder' => [
     *                 'whatsapp',
     *                 'sms',
     *                 'email',
     *                'viber'
     *             ],
     *            'invoice_paid' => [
     *               'whatsapp',
     *                'sms',
     *               'email',
     *                 'viber'
     *             ],
     *             'invoice_expired' => [
     *                 'whatsapp',
     *                 'sms',
     *                 'email',
     *                 'viber'
     *             ]
     *         ],
     *        'success_redirect_url' => 'https=>//www.google.com',
     *        'failure_redirect_url' => 'https=>//www.google.com',
     *        'currency' => 'IDR',
     *         'items' => [
     *             [
     *                'name' => 'Air Conditioner',
     *                'quantity' => 1,
     *                 'price' => 100000,
     *                 'category' => 'Electronic',
     *              'url' => 'https=>//yourcompany.com/example_item'
     *            ]
     *         ],
     *       'fees' => [
     *           [
     *                'type' => 'ADMIN',
     *               'value' => 5000
     *             ]
     *       ]
     *      ];
     */
    public function createInvoice(Payment $payment, $option_params = [])
    {

        return $this->exSave(function ()  use ($payment, $option_params) {
            $params = [
                'external_id' =>  $payment->uuid,
                'description' => 'Payment for ' . $payment->number,
                'amount' => $payment->total,
                'currency' => 'IDR',
                'success_redirect_url' => url('payment/xendit-invoice-paid/' . $payment->uuid),
            ];

            Log::info('Invoice Xendit');
            Log::info(array_merge($option_params, $params));

            return \Xendit\Invoice::create(array_merge($option_params, $params));
        });
    }

    public function createAccount($params)
    {
        return $this->exSave(function ()  use ($params) {
            return \Xendit\Platform::createAccount($params);
        });
    }

    public function updateAccount($id, $params)
    {
        return $this->exSave(function ()  use ($id, $params) {
            return \Xendit\Platform::updateAccount($id, $params);
        });
    }


    public function setCallbackURL($type, $params)
    {
        return $this->exSave(function ()  use ($type, $params) {
            return \Xendit\Platform::setCallbackUrl($type, $params);
        });
    }

    private function exSave($func)
    {
        try {
            return $func();
        } catch (\Throwable $th) {
            $code = $th->getCode();
            $msg = $th->getMessage();
            if ($code == 404) {
                if (empty($msg)) {
                    abort($code, 'Xendit: Data Not Found');
                }
                abort($code, 'Xendit: ' . $msg);
            } else {
                abort(!empty($code) ? $code : 500, 'Xendit: ' . $msg);
            }
        }
    }
}
