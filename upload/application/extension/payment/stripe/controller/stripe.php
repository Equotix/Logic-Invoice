<?php
defined('_PATH') or die('Restricted!');
require_once('include/init.php');

class ControllerPaymentStripeStripe extends Controller {
	public function index() {
		$this->data = $this->load->language('payment/stripe/stripe');

		$this->data['testmode'] = $this->config->get('stripe_testmode');

		if (!$this->config->get('stripe_testmode')) {
			\Stripe\Stripe::setApiKey($this->config->get('stripe_secret_key'));
			$this->data['api_key'] = $this->config->get('stripe_public_key');
		} else {
			\Stripe\Stripe::setApiKey($this->config->get('stripe_test_secret_key'));
			$this->data['api_key'] = $this->config->get('stripe_test_public_key');
		}

		$this->data['action'] = $this->url->link('payment/stripe/stripe/callback', '', true);

		$this->load->model('billing/invoice');

		$invoice_info = $this->model_billing_invoice->getInvoice((int)$this->request->get['invoice_id'], $this->customer->getId());

		if ($invoice_info) {
			$this->data['amount'] = round($this->currency->format($invoice_info['total'], $invoice_info['currency_code'], $invoice_info['currency_value'], false) * 100);
			$this->data['currency_code'] = $invoice_info['currency_code'];
			$this->data['description'] = $this->language->get('text_invoice') . ' #' . $invoice_info['invoice_id'];
			$this->data['custom'] = $invoice_info['invoice_id'];

			$this->response->setOutput($this->render('payment/stripe/stripe'));
		}
	}

	public function callback() {
		$this->load->language('payment/stripe/stripe');

		if (isset($this->request->post['custom'])) {
			$invoice_id = (int)$this->request->post['custom'];
		} else {
			$invoice_id = 0;
		}

		$this->load->model('billing/invoice');

		$invoice_info = $this->model_billing_invoice->getInvoice($invoice_id);

		if ($invoice_info) {
			if (!$this->config->get('stripe_testmode')) {
				\Stripe\Stripe::setApiKey($this->config->get('stripe_secret_key'));
				$this->data['api_key'] = $this->config->get('stripe_public_key');
			} else {
				\Stripe\Stripe::setApiKey($this->config->get('stripe_test_secret_key'));
				$this->data['api_key'] = $this->config->get('stripe_test_public_key');
			}

			if (isset($this->request->post['stripeToken'])) {
				$amount = round($this->currency->format($invoice_info['total'], $invoice_info['currency_code'], $invoice_info['currency_value'], false) * 100);
				
				$message = '';
				
				$status_id = $this->config->get('stripe_success');

				try {
					$customer = \Stripe\Customer::create(array(
						'email'  => $invoice_info['email'],
						'source' => $this->request->post['stripeToken'],
					));

					$charge = \Stripe\Charge::create(array(
						'amount'      => $amount,
						'currency'    => $invoice_info['currency_code'],
						'customer'    => $customer->id,
						'description' => 'Invoice Payment for Invoice #' . $invoice_id
					));
				} catch (\Stripe\Error\Card $e) {
					$message = $e->getMessage();
					
					$status_id = $this->config->get('stripe_carderror');
				} catch (\Stripe\Error\InvalidRequest $e) {
					$message = $e->getMessage();
					
					$status_id = $this->config->get('stripe_invalidrequest');
				} catch (\Stripe\Error\Authentication $e) {
					$message = $e->getMessage();

					$status_id = $this->config->get('stripe_authentication');
				} catch (\Stripe\Error\ApiConnection $e) {
					$message = $e->getMessage();

					$status_id = $this->config->get('stripe_apiconnection');
				} catch (\Stripe\Error\Base $e) {
					$message = $e->getMessage();

					$status_id = $this->config->get('stripe_genericerror');
				} catch (Exception $e) {
					$message = $e->getMessage();
            
					$status_id = $this->config->get('stripe_other');
				}

				$data = array(
					'status_id' => $status_id,
					'comment'   => $message
				);

				$this->model_billing_invoice->addHistory($invoice_id, $data, true);

				$this->load->model('system/status');

				$status = $this->model_system_status->getStatus($status_id);

				$this->load->model('system/activity');

				$this->model_system_activity->addActivity(sprintf($this->language->get('text_updated'), $invoice_id, $status['name']));

				$this->response->redirect($this->url->link('account/invoice/success', 'invoice_id=' . $invoice_info['invoice_id'], true));
			}
		}
	}
}