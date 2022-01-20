<?php

declare(strict_types=1);
abstract class ModelHelper
{
    /**
     * After Find
     * =============================================================.
     * @param object $m
     * @return DataMapper
     */
    public function afterFind(?DataMapper $m = null) : DataMapper
    {
        if ($m->count() === 1) {
            $model = current($m->get_results());
            $array = false;
            if (is_array($model)) {
                $array = true;
                $model = (object) $model;
            }
            $media_key = $this->get_media();
            if ($media_key != '') {
                $model->$media_key = $model->$media_key != null ? unserialize($model->$media_key) : ['products' . US . 'product-80x80.jpg'];
                if (is_array($model->$media_key)) {
                    foreach ($model->$media_key as $key => $url) {
                        $model->$media_key[$key] = IMG . $url; //
                    }
                }
            }
            $m->set_results($array ? (array) $model : $model);
        }
        return $m;
    }

    public function get_media() : string
    {
        return isset($this->_media_img) ? $this->_media_img : '';
    }
}
