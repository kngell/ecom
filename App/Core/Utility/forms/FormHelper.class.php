<?php

declare(strict_types=1);
class FormHelper
{
    //Error & Sucess messages alert

    //get showAll data refactor
    public static function getShowAllData($model, $params)
    {
        switch (true) {
                    case isset($params['data_type']) && $params['data_type'] == 'values': //values or html template
                        if (isset($params['return_mode']) && $params['return_mode'] == 'details') { // Detals or all
                            return $model->getDetails($params['id']);
                        }
                        if (isset($params['model_method']) && !empty($params['model_method'])) {
                            $method = $params['model_method'];

                            return $model->$method($params);
                        }
                        if (isset($params['return_mode']) && $params['return_mode'] == 'index') {
                            return $model->getAllbyIndex($params['id']);
                        } else {
                            return $model->getAllItem(['return_mode' => 'class'])->get_results();
                        }
                    break;
                    case $params['data_type'] && $params['data_type'] == 'spcefics_values':
                        return $model->getAll($params);
                    break;
                    case $params['data_type'] == 'template':
                        return $model->getHtmlData($params);
                    break;
                    case isset($params['data_type']) && $params['data_type'] == 'select2': // Get select2 Data
                        return $model->getSelect2Data($params);
                    break;
                }
    }
}