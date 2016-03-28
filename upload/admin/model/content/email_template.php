<?php
defined('_PATH') or die('Restricted!');

class ModelContentEmailTemplate extends Model {
    public function addEmailTemplate($data) {
        $this->db->query("INSERT INTO " . DB_PREFIX . "email_template SET type = '" . $this->db->escape($data['type']) . "', priority = '" . (int)$data['priority'] . "', status= '" . (int)$data['status'] . "', email = '" . $this->db->escape($data['email']) . "'");

        $email_template_id = $this->db->getLastId();

        foreach ($data['description'] as $language_id => $value) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "email_template_description SET email_template_id = '" . (int)$email_template_id . "', language_id = '" . (int)$language_id . "', subject= '" . $this->db->escape($value['subject']) . "', html = '" . $this->db->escape($value['html']) . "', text = '" . $this->db->escape($value['text']) . "'");
        }

        $this->cache->delete('email_template');
    }

    public function editEmailTemplate($email_template_id, $data) {
        $this->db->query("UPDATE " . DB_PREFIX . "email_template SET type = '" . $this->db->escape($data['type']) . "', priority = '" . (int)$data['priority'] . "', status= '" . (int)$data['status'] . "', email = '" . $this->db->escape($data['email']) . "' WHERE email_template_id = '" . (int)$email_template_id . "'");

        $this->db->query("DELETE FROM " . DB_PREFIX . "email_template_description WHERE email_template_id = '" . (int)$email_template_id . "'");

        foreach ($data['description'] as $language_id => $value) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "email_template_description SET email_template_id = '" . (int)$email_template_id . "', language_id = '" . (int)$language_id . "', subject= '" . $this->db->escape($value['subject']) . "', html = '" . $this->db->escape($value['html']) . "', text = '" . $this->db->escape($value['text']) . "'");
        }

        $this->cache->delete('email_template');
    }

    public function deleteEmailTemplate($email_template_id) {
        $this->db->query("DELETE FROM " . DB_PREFIX . "email_template WHERE email_template_id = '" . (int)$email_template_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "email_template_description WHERE email_template_id = '" . (int)$email_template_id . "'");

        $this->cache->delete('email_template');
    }

    public function getEmailTemplate($email_template_id) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "email_template WHERE email_template_id = '" . (int)$email_template_id . "'");

        $description_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "email_template_description WHERE email_template_id = '" . (int)$email_template_id . "'");

        $description = array();

        foreach ($description_query->rows as $result) {
            $description[$result['language_id']] = array(
                'subject' => $result['subject'],
                'html'    => $result['html'],
                'text'    => $result['text']
            );
        }

        return array(
            'email_template_id' => $query->row['email_template_id'],
            'type'              => $query->row['type'],
            'priority'          => $query->row['priority'],
            'status'            => $query->row['status'],
            'email'             => $query->row['email'],
            'description'       => $description
        );
    }

    public function getEmailTemplateByType($type) {
        $email_template_data = $this->cache->get('email_template.' . $type);

        if (!$email_template_data) {
            $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "email_template WHERE type = '" . $this->db->escape($type) . "' AND status = '1' ORDER BY priority ASC LIMIT 1");

            if ($query->num_rows) {
                $variables_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "email_template_type WHERE '" . $this->db->escape($type) . "' LIKE CONCAT(type, '%') LIMIT 1");

                $description_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "email_template_description WHERE email_template_id = '" . (int)$query->row['email_template_id'] . "'");

                $description = array();

                foreach ($description_query->rows as $result) {
                    $description[$result['language_id']] = array(
                        'subject' => $result['subject'],
                        'html'    => $result['html'],
                        'text'    => $result['text']
                    );
                }

                $email_template_data = array(
                    'email_template_id' => $query->row['email_template_id'],
                    'type'              => $query->row['type'],
                    'priority'          => $query->row['priority'],
                    'status'            => $query->row['status'],
                    'email'             => $query->row['email'],
                    'description'       => $description,
                    'variables'         => $variables_query->row['variables']
                );

                $this->cache->set('email_template.' . $type, $email_template_data);
            } else {
                return false;
            }
        }

        return $email_template_data;
    }

    public function getEmailTemplates($data = array()) {
        $sql = "SELECT * FROM " . DB_PREFIX . "email_template et LEFT JOIN " . DB_PREFIX . "email_template_description etd ON etd.email_template_id = et.email_template_id WHERE etd.language_id = '" . (int)$this->config->get('config_language_id') . "'";

        $sort_data = array(
            'type',
            'subject',
            'priority',
            'status'
        );

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= " ORDER BY " . $data['sort'];
        } else {
            $sql .= " ORDER BY type";
        }

        if (isset($data['order']) && ($data['order'] == 'DESC')) {
            $sql .= " DESC";
        } else {
            $sql .= " ASC";
        }

        if (isset($data['start']) && isset($data['limit'])) {
            if ($data['start'] < 0) {
                $data['start'] = 0;
            }

            if ($data['limit'] < 1) {
                $data['limit'] = 20;
            }

            $sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
        }

        $query = $this->db->query($sql);

        return $query->rows;
    }

    public function getTotalEmailTemplates() {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "email_template");

        return $query->row['total'];
    }

    public function getEmailTemplateTypes() {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "email_template_type");

        return $query->rows;
    }

    public function send($data, $type, $language_id = false) {
        if (!$language_id) {
            $language_id = $this->config->get('config_language_id');
        }

        $template_info = $this->getEmailTemplateByType($type);

        if ($template_info) {
            $variables = explode(',', $template_info['variables']);

            $search = array();
            $replace = array();

            foreach ($variables as $variable) {
                $variable = trim($variable);

                if ($variable && isset($data[$variable])) {
                    $search[] = '{' . $variable . '}';
                    $replace[] = $data[$variable];
                }
            }

            $subject = str_replace($search, $replace, html_entity_decode($template_info['description'][$language_id]['subject'], ENT_QUOTES));
            $message = str_replace($search, $replace, html_entity_decode($template_info['description'][$language_id]['html'], ENT_QUOTES));

            $template_data = array();

            $template_data['name'] = $this->config->get('config_name');
            $template_data['subject'] = $subject;
            $template_data['message'] = $message;

            $html = $this->load->view('mail/general', $template_data);

            $text = str_replace($search, $replace, html_entity_decode($template_info['description'][$language_id]['text'], ENT_QUOTES));

            if ($subject && isset($data['to_email'])) {
                $mail = new Mail($this->config->get('config_mail'));
                $mail->setTo($data['to_email']);
                $mail->setFrom($this->config->get('config_email'));
                $mail->setSender($this->config->get('config_name'));
                $mail->setSubject(html_entity_decode($subject, ENT_QUOTES, 'UTF-8'));
                $mail->setText(html_entity_decode($text, ENT_QUOTES, 'UTF-8'));
                $mail->setHtml($html);
                $mail->send();

                if ($template_info['email']) {
                    $emails = explode(',', $template_info['email']);

                    foreach ($emails as $email) {
                        $mail->setTo(trim($email));
                        $mail->send();
                    }
                }
            }
        }
    }
}