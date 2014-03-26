<?php
App::import('Core', array('Folder', 'File'));
App::import('Vendor', 'phpthumb/phpthumb');

class ImageUploadBehavior extends ModelBehavior {
    public $options = array(
        'required'            => false,
        'directory'         => 'img/uploads',
        'allowed_mime'         => array('image/jpeg', 'image/pjpeg', 'image/gif', 'image/png'),
        'allowed_extension' => array('.jpg', '.jpeg', '.png', '.gif'),
        'allowed_size'        => 1048576,
        'random_filename'   => true,
        'resize' => array(
            'max' => array(
                'directory' => 'img/uploads/max',
                'width' => 320,
                'height' => 240,
                'phpThumb' => array(
                    'zc' => 0
                )
            ),

            'thumb' => array(
                'directory' => 'img/uploads/thumbs',
                'width' => 120,
                'height' => 120,
                'phpThumb' => array(
                    //'zc' => 0,
                    'far' => 1,
                    'bg'  => 'FFFFFF'
                )
            )
        )
    );

    /**
     * Array of errors
     */
    public $errors = array();

    public $__fields;

    public function load(Model $model, $config = array()){
        if($model->actsAs['ImageUpload']) {
            $config = $model->actsAs['ImageUpload'];
        }

        $config_temp = array();

        foreach($config as $field => $options){
            // Check if given field exists
            if(!$model->hasField($field)){
                unset($config[$field]);
                unset($model->data[$model->name][$field]);

                continue;
            }

            if(isset($options['directory'])){

                if(substr($options['directory'], -1) != '/'){
                    $options['directory'] = $options['directory'] . DS;
                }
            }

            $config_temp[$field] = $options;
        }
        $this->__fields = $config_temp;
    }

    public function beforeSave(Model $model, $options = array()) {
        $this->load($model);

        if(count($this->__fields) > 0) {
            foreach($this->__fields as $field => $options) {
                // Check for model data whether has been set or not
                if(!isset($model->data[$model->name][$field])){
                    continue;
                }

                // lets see if the ingore upload it set!
                if(!empty($model->data[$model->name]['noUpload'])){
                    continue;
                }

                // Check the data if it's not an array
                if(isset($model->data) && !is_array($model->data[$model->name][$field])){
                    unset($model->data[$model->name][$field]);
                    continue;
                }

                // Check any error occur
                if($model->data[$model->name][$field]['error'] > 0) {
                    // if error == 4 then we are not loading a file, so lets see if we want to delete it
                    if(!empty($model->data[$model->name][$field.'_delete'])) {
                        // lets delete the old images
                        $current = $model->findById($model->data[$model->name][$field.'_delete']);
                           if(!empty($current[$model->name][$field])) {
                               $this->removeImages($current[$model->name][$field], $options);
                           }
                        $model->data[$model->name][$field] = '';
                    } else {
                        unset($model->data[$model->name][$field]);
                    }
                    continue;
                }

                // Create final save path
                if(!isset($options['random_filename']) || !$options['random_filename']) {
                    $saveAs = realpath(WWW_ROOT . DS . $options['directory']) . DS . $model->data[$model->name][$field]['name'];
                } else {
                    // Remove any file which did exist for this model
                    if(!empty($model->data[$model->name]['id'])) {
                        $current = $model->findById($model->data[$model->name]['id']);

                        // lets delete the old images
                           if(!empty($current[$model->name][$field])) {
                               $this->removeImages($current[$model->name][$field], $options);
                           }
                    }

                    if(!isset($options['random_filename']) || !$options['random_filename']) {
                        $saveAs = realpath(WWW_ROOT . DS . $options['directory']) .DS. $model->data[$model->name][$field]['name'];
                    } else {
                        $uniqueFileName = sha1(uniqid(rand(), true));
                        $extension = explode('.', $model->data[$model->name][$field]['name']);
                        $saveAs    = realpath(WWW_ROOT . DS . $options['directory']) .DS. $uniqueFileName . '.' . $extension[count($extension)-1];
                    }
                }

                // Attempt to move uploaded file
                if(!move_uploaded_file($model->data[$model->name][$field]['tmp_name'], $saveAs)) {
                    unset($model->data[$model->name][$field]);
                    continue;
                }

                // Update model data
                //$model->data[$model->name]['type'] = $model->data[$model->name][$field]['type'];
                //$model->data[$model->name]['size'] = $model->data[$model->name][$field]['size'];
                $model->data[$model->name][$field] = basename($saveAs);

                if(!empty($options['resize'])){
                    foreach($options['resize'] as $name => $resize){
                        $this->generateThumbnail($saveAs, $resize);
                    }
                }
            }
        }

        return true;
    }

    public function beforeValidate(Model $model, $options = array()) {
        $this->load($model);

        foreach($this->__fields as $field => $options) {
            if(!empty($model->data[$model->name][$field]['type']) && !empty($options['allowed_mime'])) {
                // Check extensions
                if(count($options['allowed_extension']) > 0) {
                    $matches = 0;
                    foreach($options['allowed_extension'] as $extension){
                        if(strtolower(substr($model->data[$model->name][$field]['name'],-strlen($extension))) == $extension){
                            $matches++;
                        }
                    }

                    if($matches == 0) {
                        $allowed_ext = implode(', ', $options['allowed_extension']);
                        $model->invalidate($field, __('L\'image doit être dans l\'un des types suivants: %s.', $allowed_ext));
                        continue;
                    }
                }

                // Check mime
                if(count($options['allowed_mime']) > 0 && !in_array($model->data[$model->name][$field]['type'], $options['allowed_mime'])) {
                    $model->invalidate($field, __('Type d\'image invalide.'));
                    continue;
                }

                // Check the size
                if($model->data[$model->name][$field]['size'] > $options['allowed_size']) {
                    $model->invalidate($field, __('La taille maximale de l\'image est de %d MO', $options['allowed_size']));
                    continue;
                }
            } else {
                if(is_array($options['required'])) {
                    foreach ($options['required'] as $action => $required) {
                        $empty = false;

                        switch ($action){
                            case 'add':
                                if($required == true && empty($model->data[$model->name]['id'])){
                                    $empty = true;
                                    continue;
                                }
                                break;

                            case 'edit':
                                if($required == true && !empty($model->data[$model->name]['id'])){
                                    $empty = true;
                                    continue;
                                }
                                break;
                        }

                        if ($empty){
                            $model->invalidate($field, __('Le champ %s est obligatoire.', Inflector::humanize($field)));
                            continue;
                        }
                    }
                } elseif ($options['required'] == true) {
                    $model->invalidate($field, sprintf(__('Le champ %s est obligatoire.'), Inflector::humanize($field)));
                    continue;
                }
            }
        }
    }

    public function beforeDelete(Model $model, $cascade = true) {
        $this->load($model);

        if (count($this->__fields) > 0){
            $model->read(null, $model->id);
            if (isset($model->data)) {
                foreach($this->__fields as $field => $options){
                    if(!empty($model->data[$model->name][$field])) {
                        $count = $model->find('count', array('conditions' => array($field => $model->data[$model->name][$field])));
                        if($count == 1) {
                            $this->removeImages($model->data[$model->name][$field], $options);
                        }
                    }
                }
            }
        }
        return true;
    }

    public function removeImages($file, $options) {
        $file_with_ext = WWW_ROOT . $options['directory'] . $file;

        if (file_exists($file_with_ext)) {
            unlink($file_with_ext);
        }

        foreach ($options['resize'] as $name => $resize) {
            $resizePath = WWW_ROOT . DS . $resize['directory'] . $file;
            if(file_exists($resizePath)) {
                unlink($resizePath);
            }
        }
    }

    public function generateThumbnail($saveAs, $options){
        $destination = WWW_ROOT . $options['directory'] . DS . basename($saveAs);

        $ext = substr(basename($saveAs), strrpos(basename($saveAs), '.') + 1);
        if ($ext == '.jpg' || $ext == '.jpeg') {
            $format = 'jpeg';
        } elseif ($ext == 'png') {
            $format = 'png';
        } elseif ($ext == 'gif') {
            $format = 'gif';
        } else {
            $format = 'jpeg';
        }

        $phpThumb = new phpthumb();
        $phpThumb->setSourceFilename($saveAs);
        $phpThumb->setCacheDirectory(CACHE);

        // lets see if we are going to reduce the height and width
        // we won't do it on a small image!
        $size = getimagesize($saveAs);
        if ($size[0] > $options['width'] || !empty($options['phpThumb']['far'])) {
            $phpThumb->setParameter('w', $options['width']);
        }

        if ($size[1] > $options['height'] || !empty($options['phpThumb']['far'])) {
            $phpThumb->setParameter('h', $options['height']);
        }

        $phpThumb->setParameter('f', $format);

        if (!empty($options['phpThumb'])) {
            foreach($options['phpThumb'] as $name => $value) {
                if(!empty($value)) {
                    $phpThumb->setParameter($name, $value);
                }
            }
        }

        if ($phpThumb->generateThumbnail()){
            if($phpThumb->RenderToFile($destination)){
                chmod($destination, 0644);
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
}