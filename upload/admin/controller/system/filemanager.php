<?php
class ControllerSystemFileManager extends Controller {
    public function index() {
        $this->data = $this->load->language('system/filemanager');

        if (isset($this->request->get['filter_name'])) {
            $filter_name = rtrim(str_replace(array(
                '../',
                '..\\',
                '..',
                '*'
            ), '', $this->request->get['filter_name']), '/');
        } else {
            $filter_name = null;
        }

        // Make sure we have the correct directory
        if (isset($this->request->get['directory'])) {
            $directory = rtrim(DIR_IMAGE . 'upload/' . str_replace(array(
                    '../',
                    '..\\',
                    '..'
                ), '', $this->request->get['directory']), '/');
        } else {
            $directory = DIR_IMAGE . 'upload';
        }

        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }

        $data['images'] = array();

        $this->load->model('tool/image');

        // Get directories
        $directories = glob($directory . '/' . $filter_name . '*', GLOB_ONLYDIR);

        if (!$directories) {
            $directories = array();
        }

        // Get files
        $files = glob($directory . '/' . $filter_name . '*.{jpg,jpeg,png,gif,JPG,JPEG,PNG,GIF}', GLOB_BRACE);

        if (!$files) {
            $files = array();
        }

        // Merge directories and files
        $images = array_merge($directories, $files);

        // Get total number of files and directories
        $image_total = count($images);

        // Split the array based on current page number and max number of items per page of 10
        $images = array_splice($images, ($page - 1) * 16, 16);

        foreach ($images as $image) {
            $name = str_split(basename($image), 14);

            if (is_dir($image)) {
                $url = '';

                if (isset($this->request->get['target'])) {
                    $url .= '&target=' . $this->request->get['target'];
                }

                if (isset($this->request->get['thumb'])) {
                    $url .= '&thumb=' . $this->request->get['thumb'];
                }

                $this->data['images'][] = array(
                    'thumb' => '',
                    'name'  => implode(' ', $name),
                    'type'  => 'directory',
                    'path'  => utf8_substr($image, utf8_strlen(DIR_IMAGE)),
                    'href'  => $this->url->link('system/filemanager', 'token=' . $this->session->data['token'] . '&directory=' . urlencode(utf8_substr($image, utf8_strlen(DIR_IMAGE . 'upload/'))) . $url, true)
                );
            } elseif (is_file($image)) {
                // Find which protocol to use to pass the full image link back
                if ($this->request->server['HTTPS']) {
                    $server = HTTPS_APPLICATION;
                } else {
                    $server = HTTP_APPLICATION;
                }

                $this->data['images'][] = array(
                    'thumb' => $this->model_tool_image->resize(utf8_substr($image, utf8_strlen(DIR_IMAGE)), 100, 100),
                    'name'  => implode(' ', $name),
                    'type'  => 'image',
                    'path'  => utf8_substr($image, utf8_strlen(DIR_IMAGE)),
                    'href'  => $server . 'image/' . utf8_substr($image, utf8_strlen(DIR_IMAGE))
                );
            }
        }

        $this->data['token'] = $this->session->data['token'];

        if (isset($this->request->get['directory'])) {
            $this->data['directory'] = urlencode($this->request->get['directory']);
        } else {
            $this->data['directory'] = '';
        }

        if (isset($this->request->get['filter_name'])) {
            $this->data['filter_name'] = $this->request->get['filter_name'];
        } else {
            $this->data['filter_name'] = '';
        }

        // Return the target ID for the file manager to set the value
        if (isset($this->request->get['target'])) {
            $this->data['target'] = $this->request->get['target'];
        } else {
            $this->data['target'] = '';
        }

        // Return the thumbnail for the file manager to show a thumbnail
        if (isset($this->request->get['thumb'])) {
            $this->data['thumb'] = $this->request->get['thumb'];
        } else {
            $this->data['thumb'] = '';
        }

        // Parent
        $url = '';

        if (isset($this->request->get['directory'])) {
            $pos = strrpos($this->request->get['directory'], '/');

            if ($pos) {
                $url .= '&directory=' . urlencode(substr($this->request->get['directory'], 0, $pos));
            }
        }

        if (isset($this->request->get['target'])) {
            $url .= '&target=' . $this->request->get['target'];
        }

        if (isset($this->request->get['thumb'])) {
            $url .= '&thumb=' . $this->request->get['thumb'];
        }

        $this->data['parent'] = $this->url->link('system/filemanager', 'token=' . $this->session->data['token'] . $url, true);

        // Refresh
        $url = '';

        if (isset($this->request->get['directory'])) {
            $url .= '&directory=' . urlencode($this->request->get['directory']);
        }

        if (isset($this->request->get['target'])) {
            $url .= '&target=' . $this->request->get['target'];
        }

        if (isset($this->request->get['thumb'])) {
            $url .= '&thumb=' . $this->request->get['thumb'];
        }

        $this->data['refresh'] = $this->url->link('system/filemanager', 'token=' . $this->session->data['token'] . $url, true);

        $url = '';

        if (isset($this->request->get['directory'])) {
            $url .= '&directory=' . urlencode(html_entity_decode($this->request->get['directory'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_name'])) {
            $url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['target'])) {
            $url .= '&target=' . $this->request->get['target'];
        }

        if (isset($this->request->get['thumb'])) {
            $url .= '&thumb=' . $this->request->get['thumb'];
        }

        $pagination = new Pagination();
        $pagination->total = $image_total;
        $pagination->page = $page;
        $pagination->limit = 16;
        $pagination->url = $this->url->link('system/filemanager', 'token=' . $this->session->data['token'] . $url . '&page={page}', true);

        $this->data['pagination'] = $pagination->render();

        $this->response->setOutput($this->render('system/filemanager'));
    }

    public function upload() {
        $this->load->language('system/filemanager');

        $json = array();

        // Check user has permission
        if (!$this->user->hasPermission('modify', 'system/filemanager')) {
            $json['error'] = $this->language->get('error_permission');
        }

        // Make sure we have the correct directory
        if (isset($this->request->get['directory'])) {
            $directory = rtrim(DIR_IMAGE . 'upload/' . str_replace(array(
                    '../',
                    '..\\',
                    '..'
                ), '', $this->request->get['directory']), '/');
        } else {
            $directory = DIR_IMAGE . 'upload';
        }

        // Check its a directory
        if (!is_dir($directory)) {
            $json['error'] = $this->language->get('error_directory');
        }

        if (!$json) {
            if (!empty($this->request->files['file']['name']) && is_file($this->request->files['file']['tmp_name'])) {
                // Sanitize the filename
                $filename = basename(html_entity_decode($this->request->files['file']['name'], ENT_QUOTES, 'UTF-8'));

                // Validate the filename length
                if ((utf8_strlen($filename) < 3) || (utf8_strlen($filename) > 255)) {
                    $json['error'] = $this->language->get('error_filename');
                }

                // Allowed file extension types
                $allowed = array(
                    'jpg',
                    'jpeg',
                    'gif',
                    'png'
                );

                if (!in_array(utf8_strtolower(utf8_substr(strrchr($filename, '.'), 1)), $allowed)) {
                    $json['error'] = $this->language->get('error_filetype');
                }

                // Allowed file mime types
                $allowed = array(
                    'image/jpeg',
                    'image/pjpeg',
                    'image/png',
                    'image/x-png',
                    'image/gif'
                );

                if (!in_array($this->request->files['file']['type'], $allowed)) {
                    $json['error'] = $this->language->get('error_filetype');
                }

                // Check to see if any PHP files are being uploaded
                $content = file_get_contents($this->request->files['file']['tmp_name']);

                if (preg_match('/\<\?php/i', $content)) {
                    $json['error'] = $this->language->get('error_filetype');
                }

                // Return any upload error
                if ($this->request->files['file']['error'] != UPLOAD_ERR_OK) {
                    $json['error'] = $this->language->get('error_upload_' . $this->request->files['file']['error']);
                }
            } else {
                $json['error'] = $this->language->get('error_upload');
            }
        }

        if (!$json) {
            move_uploaded_file($this->request->files['file']['tmp_name'], $directory . '/' . $filename);

            $json['success'] = $this->language->get('text_uploaded');
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function folder() {
        $this->load->language('system/filemanager');

        $json = array();

        // Check user has permission
        if (!$this->user->hasPermission('modify', 'system/filemanager')) {
            $json['error'] = $this->language->get('error_permission');
        }

        // Make sure we have the correct directory
        if (isset($this->request->get['directory'])) {
            $directory = rtrim(DIR_IMAGE . 'upload/' . str_replace(array(
                    '../',
                    '..\\',
                    '..'
                ), '', $this->request->get['directory']), '/');
        } else {
            $directory = DIR_IMAGE . 'upload';
        }

        // Check its a directory
        if (!is_dir($directory)) {
            $json['error'] = $this->language->get('error_directory');
        }

        if (!$json) {
            // Sanitize the folder name
            $folder = str_replace(array(
                '../',
                '..\\',
                '..'
            ), '', basename(html_entity_decode($this->request->post['folder'], ENT_QUOTES, 'UTF-8')));

            // Validate the filename length
            if ((utf8_strlen($folder) < 3) || (utf8_strlen($folder) > 128)) {
                $json['error'] = $this->language->get('error_folder');
            }

            // Check if directory already exists or not
            if (is_dir($directory . '/' . $folder)) {
                $json['error'] = $this->language->get('error_exists');
            }
        }

        if (!$json) {
            mkdir($directory . '/' . $folder, 0777);

            $json['success'] = $this->language->get('text_directory');
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function delete() {
        $this->load->language('system/filemanager');

        $json = array();

        // Check user has permission
        if (!$this->user->hasPermission('modify', 'system/filemanager')) {
            $json['error'] = $this->language->get('error_permission');
        }

        if (isset($this->request->post['path'])) {
            $paths = $this->request->post['path'];
        } else {
            $paths = array();
        }

        // Loop through each path to run validations
        foreach ($paths as $path) {
            $path = rtrim(DIR_IMAGE . str_replace(array(
                    '../',
                    '..\\',
                    '..'
                ), '', $path), '/');

            // Check path exsists
            if ($path == DIR_IMAGE . 'upload') {
                $json['error'] = $this->language->get('error_delete');

                break;
            }
        }

        if (!$json) {
            // Loop through each path
            foreach ($paths as $path) {
                $path = rtrim(DIR_IMAGE . str_replace(array(
                        '../',
                        '..\\',
                        '..'
                    ), '', $path), '/');

                // If path is just a file delete it
                if (is_file($path)) {
                    unlink($path);

                    // If path is a directory beging deleting each file and sub folder
                } elseif (is_dir($path)) {
                    $files = array();

                    // Make path into an array
                    $path = array($path . '*');

                    // While the path array is still populated keep looping through
                    while (count($path) != 0) {
                        $next = array_shift($path);

                        foreach (glob($next) as $file) {
                            // If directory add to path array
                            if (is_dir($file)) {
                                $path[] = $file . '/*';
                            }

                            // Add the file to the files to be deleted array
                            $files[] = $file;
                        }
                    }

                    // Reverse sort the file array
                    rsort($files);

                    foreach ($files as $file) {
                        // If file just delete
                        if (is_file($file)) {
                            unlink($file);

                            // If directory use the remove directory function
                        } elseif (is_dir($file)) {
                            rmdir($file);
                        }
                    }
                }
            }

            $json['success'] = $this->language->get('text_delete');
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
}