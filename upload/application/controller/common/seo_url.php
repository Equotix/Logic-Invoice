<?php
defined('_PATH') or die('Restricted!');

class ControllerCommonSeoUrl extends Controller {
    public function index() {
        // Add rewrite to url class
        if ($this->config->get('config_seo_url')) {
            $this->url->addRewrite($this);
        }

        // Decode URL
        if (isset($this->request->get['_load_'])) {
            $parts = explode('/', $this->request->get['_load_']);

            if (utf8_strlen(end($parts)) == 0) {
                array_pop($parts);
            }

            foreach ($parts as $part) {
                $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "url_alias WHERE language_id = '" . (int)$this->config->get('config_language_id') . "' AND keyword = '" . $this->db->escape($part) . "'");

                if ($query->num_rows) {
                    $url = explode('=', $query->row['query']);

                    if ($url[0] == 'article_id') {
                        $this->request->get['article_id'] = $url[1];
                    } elseif ($url[0] == 'blog_category_id') {
                        if (!isset($this->request->get['blog_category_id'])) {
                            $this->request->get['blog_category_id'] = $url[1];
                        } else {
                            $this->request->get['blog_category_id'] .= '_' . $url[1];
                        }
                    } elseif ($url[0] == 'blog_post_id') {
                        $this->request->get['blog_post_id'] = $url[1];
                    }
                } else {
                    $this->request->get['load'] = 'error/not_found';

                    break;
                }
            }

            if (!isset($this->request->get['load'])) {
                if (isset($this->request->get['article_id'])) {
                    $this->request->get['load'] = 'content/article';
                } elseif (isset($this->request->get['blog_post_id'])) {
                    $this->request->get['load'] = 'content/blog/info';
                } elseif (isset($this->request->get['blog_category_id'])) {
                    $this->request->get['load'] = 'content/blog';
                }
            }

            if (isset($this->request->get['load']) && $this->request->get['load'] == 'error/not_found') {
                $load = '';
                $exists = false;

                if (isset($parts[0]) && ($parts[0] == 'module' || $parts[0] == 'payment' || $parts[0] == 'total')) {
                    if (isset($parts[1])) {
                        $load .= $parts[0] . '/' . $parts[1] . '/';

                        $file = DIR_EXTENSION . $parts[0] . '/' . $parts[1] . '/controller/';

                        array_shift($parts);

                        array_shift($parts);

                        foreach ($parts as $part) {
                            $file .= $part;

                            $load .= $part . '/';

                            if (is_dir(str_replace(array(
                                '../',
                                '..\\',
                                '..'
                            ), '', $file))) {
                                $file .= '/';

                                continue;
                            } elseif (is_file(str_replace(array(
                                    '../',
                                    '..\\',
                                    '..'
                                ), '', $file) . '.php')) {
                                $exists = true;
                            }
                        }
                    }
                } else {
                    foreach ($parts as $part) {
                        $load .= $part;

                        if (is_dir(DIR_APPLICATION . 'controller/' . str_replace(array(
                                '../',
                                '..\\',
                                '..'
                            ), '', $load))) {
                            $load .= '/';
                            continue;
                        } elseif (is_file(DIR_APPLICATION . 'controller/' . str_replace(array(
                                '../',
                                '..\\',
                                '..'
                            ), '', $load) . '.php')) {
                            $load .= '/';

                            $exists = true;
                        }
                    }
                }

                if ($exists == true) {
                    $this->request->get['load'] = $load;
                }
            }

            if (isset($this->request->get['load'])) {
                return new Action($this->request->get['load']);
            }
        }
    }

    public function rewrite($link) {
        $url_data = parse_url(str_replace('&amp;', '&', $link));

        $url = '';

        $data = array();

        parse_str($url_data['query'], $data);

        foreach ($data as $key => $value) {
            if (isset($data['load'])) {
                if (($data['load'] == 'content/article' && $key == 'article_id') || ($data['load'] == 'content/blog/info' && $key == 'blog_post_id')) {
                    $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "url_alias WHERE language_id = '" . (int)$this->config->get('config_language_id') . "' AND `query` = '" . $this->db->escape($key . '=' . (int)$value) . "'");

                    if ($query->num_rows) {
                        $url .= '/' . $query->row['keyword'];

                        unset($data[$key]);
                    }
                } elseif ($key == 'blog_category_id') {
                    $blog_category_ids = explode('_', (string)$value);

                    foreach ($blog_category_ids as $blog_category_id) {
                        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "url_alias WHERE language_id = '" . (int)$this->config->get('config_language_id') . "' AND `query` = '" . $this->db->escape($key . '=' . (int)$blog_category_id) . "'");

                        if ($query->num_rows) {
                            $url .= '/' . $query->row['keyword'];

                            unset($data[$key]);
                        }
                    }
                } elseif ($key == 'load') {
                    if ($data['load'] == 'common/home') {
                        $url .= '/';
                    } elseif (!($data['load'] == 'content/article' || ($data['load'] == 'content/blog' && isset($data['blog_category_id'])) || $data['load'] == 'content/blog/info')) {
                        $url .= '/' . $data['load'];
                    }
                }
            }
        }

        if ($url) {
            unset($data['load']);

            $query = '';

            if ($data) {
                foreach ($data as $key => $value) {
                    $query .= '&' . rawurlencode((string)$key) . '=' . rawurlencode((string)$value);
                }

                if ($query) {
                    $query = '?' . str_replace('&', '&amp;', trim($query, '&'));
                }
            }

            return $url_data['scheme'] . '://' . $url_data['host'] . (isset($url_data['port']) ? ':' . $url_data['port'] : '') . str_replace('/index.php', '', $url_data['path']) . $url . $query;
        } else {
            return $link;
        }
    }
}