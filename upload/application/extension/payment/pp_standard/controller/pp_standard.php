<?php
defined('_PATH') or die('Restricted!');

class ControllerPaymentPPStandardPPStandard extends Controller {
    public function index() {
        $this->data = $this->load->language('payment/pp_standard/pp_standard');

        $this->data['sandbox'] = $this->config->get('pp_standard_sandbox');

        if (!$this->config->get('pp_standard_sandbox')) {
            $this->data['action'] = 'https://www.paypal.com/cgi-bin/webscr';
        } else {
            $this->data['action'] = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
        }

        $this->load->model('billing/invoice');

        $invoice_info = $this->model_billing_invoice->getInvoice((int)$this->request->get['invoice_id'], $this->customer->getId());

        if ($invoice_info) {
            $this->data['business'] = $this->config->get('pp_standard_email');
            $this->data['item_name'] = html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8');

            $this->data['items'] = array();

            $discount = 0;

            foreach ($invoice_info['items'] as $item) {
                if ((float)$item['discount']) {
                    $discount += $this->currency->format($item['discount'] * $item['quantity'], $invoice_info['currency_code'], $invoice_info['currency_value'], false);
                }

                $this->data['items'][] = array(
                    'title' => html_entity_decode($item['title'], ENT_QUOTES),
                    'price' => $this->currency->format($item['price'] * $item['quantity'], $invoice_info['currency_code'], $invoice_info['currency_value'], false)
                );
            }

            foreach ($invoice_info['totals'] as $total) {
                if (!($total['code'] == 'sub_total' || $total['code'] == 'total')) {
                    $this->data['items'][] = array(
                        'title' => html_entity_decode($total['title'], ENT_QUOTES),
                        'price' => $this->currency->format($total['value'], $invoice_info['currency_code'], $invoice_info['currency_value'], false)
                    );
                }
            }

            $this->data['discount_amount_cart'] = $discount;

            $this->data['currency_code'] = $invoice_info['currency_code'];
            $this->data['first_name'] = html_entity_decode($invoice_info['firstname'], ENT_QUOTES, 'UTF-8');
            $this->data['last_name'] = html_entity_decode($invoice_info['lastname'], ENT_QUOTES, 'UTF-8');
            $this->data['email'] = $invoice_info['email'];
            $this->data['invoice'] = $invoice_info['invoice_id'] . ' - ' . html_entity_decode($invoice_info['firstname'], ENT_QUOTES, 'UTF-8') . ' ' . html_entity_decode($invoice_info['lastname'], ENT_QUOTES, 'UTF-8');
            $this->data['lc'] = $this->session->data['language'];
            $this->data['return'] = $this->url->link('account/invoice/success', 'invoice_id=' . $invoice_info['invoice_id'], true);
            $this->data['notify_url'] = $this->url->link('payment/pp_standard/pp_standard/callback', '', true);
            $this->data['cancel_return'] = $this->url->link('account/invoice/payment', 'invoice_id=' . $invoice_info['invoice_id'], true);

            if (!$this->config->get('pp_standard_transaction')) {
                $this->data['paymentaction'] = 'authorization';
            } else {
                $this->data['paymentaction'] = 'sale';
            }

            $this->data['custom'] = $invoice_info['invoice_id'];

            $this->response->setOutput($this->render('payment/pp_standard/pp_standard'));
        }
    }

    public function callback() {
        $this->load->language('payment/pp_standard/pp_standard');

        if (isset($this->request->post['custom'])) {
            $invoice_id = (int)$this->request->post['custom'];
        } else {
            $invoice_id = 0;
        }

        $this->load->model('billing/invoice');

        $invoice_info = $this->model_billing_invoice->getInvoice($invoice_id);

        if ($invoice_info) {
            $request = 'cmd=_notify-validate';

            foreach ($this->request->post as $key => $value) {
                $request .= '&' . $key . '=' . urlencode(html_entity_decode($value, ENT_QUOTES, 'UTF-8'));
            }

            if (!$this->config->get('pp_standard_sandbox')) {
                $curl = curl_init('https://www.paypal.com/cgi-bin/webscr');
            } else {
                $curl = curl_init('https://www.sandbox.paypal.com/cgi-bin/webscr');
            }

            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $request);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_HEADER, false);
            curl_setopt($curl, CURLOPT_TIMEOUT, 30);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

            $response = curl_exec($curl);

            if (!$response) {
                $this->log->write('PP_STANDARD :: CURL failed ' . curl_error($curl) . '(' . curl_errno($curl) . ')');
            }

            if ($this->config->get('pp_standard_debug')) {
                $this->log->write('PP_STANDARD :: IPN REQUEST: ' . $request);
                $this->log->write('PP_STANDARD :: IPN RESPONSE: ' . $response);
            }

            $status_id = $this->config->get('pp_standard_denied');

            $message = '';

            if ((strcmp($response, 'VERIFIED') == 0 || strcmp($response, 'UNVERIFIED') == 0) && isset($this->request->post['payment_status'])) {
                switch ($this->request->post['payment_status']) {
                    case 'Canceled_Reversal':
                        $status_id = $this->config->get('pp_standard_cancelled');
                        break;
                    case 'Completed':
                        $receiver_match = (strtolower($this->request->post['receiver_email']) == strtolower($this->config->get('pp_standard_email')));
                        $total_paid_match = ((float)$this->request->post['mc_gross'] == $this->currency->format($invoice_info['total'], $invoice_info['currency_code'], $invoice_info['currency_value'], false));

                        if ($receiver_match && $total_paid_match) {
                            $status_id = $this->config->get('pp_standard_completed');
                        } else {
                            if (!$receiver_match) {
                                $message = 'Receiver email mismatch. ' . strtolower($this->request->post['receiver_email']);
                            }

                            if (!$total_paid_match) {
                                $message = 'Total paid mismatch. ' . $this->request->post['mc_gross'];
                            }
                        }
                        break;
                    case 'Denied':
                        $status_id = $this->config->get('pp_standard_denied');
                        break;
                    case 'Expired':
                        $status_id = $this->config->get('pp_standard_expired');
                        break;
                    case 'Failed':
                        $status_id = $this->config->get('pp_standard_failed');
                        break;
                    case 'Pending':
                        $status_id = $this->config->get('pp_standard_pending');
                        break;
                    case 'Processed':
                        $status_id = $this->config->get('pp_standard_processed');
                        break;
                    case 'Refunded':
                        $status_id = $this->config->get('pp_standard_refunded');
                        break;
                    case 'Reversed':
                        $status_id = $this->config->get('pp_standard_reversed');
                        break;
                    case 'Voided':
                        $status_id = $this->config->get('pp_standard_voided');
                        break;
                }
            }

            $data = $invoice_info;

            if (isset($this->request->post['first_name'])) {
                $data['payment_firstname'] = $this->request->post['first_name'];
            }

            if (isset($this->request->post['last_name'])) {
                $data['payment_lastname'] = $this->request->post['last_name'];
            }

            if (isset($this->request->post['address_street'])) {
                $data['payment_address_1'] = $this->request->post['address_street'];
            }

            if (isset($this->request->post['address_city'])) {
                $data['payment_city'] = $this->request->post['address_city'];
            }

            if (isset($this->request->post['address_zip'])) {
                $data['payment_postcode'] = $this->request->post['address_zip'];
            }

            if (isset($this->request->post['address_country'])) {
                $data['payment_country'] = $this->request->post['address_country'];
            }

            if (isset($this->request->post['address_state'])) {
                $data['payment_zone'] = $this->request->post['address_state'];
            }

            $this->model_billing_invoice->editInvoice($invoice_id, $data);

            $data = array(
                'status_id' => $status_id,
                'comment'   => $message
            );

            $this->model_billing_invoice->addHistory($invoice_id, $data, true);

            $this->load->model('system/status');

            $status = $this->model_system_status->getStatus($status_id);

            $this->load->model('system/activity');

            $this->model_system_activity->addActivity(sprintf($this->language->get('text_updated'), $invoice_id, $status['name']));

            curl_close($curl);
        }
    }
}