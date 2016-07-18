<?php
class ControllerInstallStep3 extends Controller {
    public function index() {
        $this->data = $this->load->language('default');

        $this->data['databases'] = array(
            'mysqli'
        );

        $this->data['prefix'] = substr(str_shuffle('abcdefghijklmnopqrstuvwxyz'), 0, 5) . '_';

        $this->response->setOutput($this->render('install/step_3'));
    }

    public function validate() {
        $json = array();

        if ($this->request->post['database'] == 'mysqli') {
            $connection = @new mysqli($this->request->post['database_hostname'], $this->request->post['database_username'], $this->request->post['database_password'], $this->request->post['database_name']);

            if ($connection->connect_error) {
                $json['error'] = $connection->connect_error;
            } else {
                $connection->close();
            }
        }

        if ($this->request->post['database'] == 'mysql') {
            $connection = @mysql_connect($this->request->post['database_hostname'], $this->request->post['database_username'], $this->request->post['database_password']);

            if (!$connection) {
                $json['error'] = $this->language->get('error_connection');
            } else {
                if (!@mysql_select_db($this->request->post['database_name'], $connection)) {
                    $json['error'] = $this->language->get('error_database');
                }

                mysql_close($connection);
            }
        }

        if ((utf8_strlen($this->request->post['admin_username']) < 3) || (utf8_strlen($this->request->post['admin_username']) > 32)) {
            $json['error'] = $this->language->get('error_username');
        }

        if ((utf8_strlen($this->request->post['admin_password']) < 6) || (utf8_strlen($this->request->post['admin_password']) > 25)) {
            $json['error'] = $this->language->get('error_password');
        }

        if ((utf8_strlen($this->request->post['admin_email']) > 96) || !preg_match('/^[^\@]+@.*.[a-z]{2,15}$/i', $this->request->post['admin_email'])) {
            $json['error'] = $this->language->get('error_email');
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
}