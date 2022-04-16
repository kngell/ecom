<?php

declare(strict_types=1);
class FH
{
    public static function csrfInput($name, $token)
    {
        return '<input type="hidden" name="' . $name . '" value="' . $token . '" />';
    }

    //Error & Sucess messages alert

    public static function showMessage(string $type, mixed $message) : string
    {
        if (is_array($message)) {
            $output = '<div class="align-self-center text-center alert alert-' . $type . ' alert-dismissible">
            <button type="button" class="btn-close" data-bs-dismiss="alert"><span class="float-end"></span></button>
            <strong class="text-center">';
            foreach ($message as $msg) {
                $output .= $msg;
            }
            $output .= '</strong></div>';

            return $output;
        }

        return '<div class="align-self-center text-center alert alert-' . $type . ' alert-dismissible">
                    <button type="button" class="btn-close" data-bs-dismiss="alert"><span class="float-end"></span></button>
                    <strong class="text-center">' . $message . '</strong>
              </div>';
    }

    public static function checkoutSuccessMsg($intentResponse)
    {
        $template = file_get_contents(FILES . 'template' . DS . 'e_commerce' . DS . 'payment' . DS . 'successMessageTemplate.php');
        $template = str_replace('{{transactionID}}', $intentResponse->id, $template);
        $template = str_replace('{{link1}}', PROOT . 'home' . US . 'cart', $template);
        $template = str_replace('{{link2}}', PROOT . 'home' . US . 'boutique', $template);

        return $template;
    }

    public static function AssignErrors($source, $errors)
    {
        $frm_errors = $errors;
        $a_errors = new stdClass;
        foreach ($source as $key => $val) {
            $a_errors->$key = '';
            if (!empty($frm_errors)) {
                foreach ($frm_errors as $k => $v) {
                    if ($key == $k) {
                        $a_errors->$key = $v;
                        array_shift($frm_errors);
                    }
                }
            }
        }

        return $a_errors;
    }

    public static function validate_forms(array $items, ModelInterface $obj)
    {
        $container = Container::getInstance();
        foreach ($items as $item => $rules) {
            $display = $rules['display'];
            $value = $obj->getEntity()->{'get' . ucwords($item)}();
            if (isset($value)) {
                foreach ($rules as $rule => $rule_value) {
                    if ($rule === 'required' && (empty($value) || $value == '[]')) {
                        $requireMsg = ($item == 'terms') ? 'Please accept terms & conditions' : "{$display} is require";
                        $obj->runValidation($container->make(Requirevalidator::class, [
                            'model' => $obj,
                            'field' => $item,
                            'rule' => $rule_value,
                            'msg' => $requireMsg,
                        ]));
                    } elseif (!empty($value)) {
                        switch ($rule) {
                        case 'min':
                            $obj->runValidation($container->make(Minvalidator::class, [
                                'model' => $obj,
                                'field' => $item,
                                'rule' => $rule_value,
                                'msg' => "{$display} must be a minimum of {$rule_value} characters",
                            ]));
                            break;
                        case 'max':
                            $obj->runValidation($container->make(Maxvalidator::class, [
                                'model' => $obj,
                                'field' => $item,
                                'rule' => $rule_value,
                                'msg' => "{$display} must be a maximum of {$rule_value} caracters",
                            ]));
                            break;
                        case 'valid_email':
                            $obj->runValidation($container->make(ValidEmailvalidator::class, [
                                'model' => $obj,
                                'field' => $item,
                                'rule' => $rule_value,
                                'msg' => "{$display} is not valid Email",
                            ]));
                            break;
                        case 'is_numeric':
                            $obj->runValidation($container->make(Numericvalidator::class, [
                                'model' => $obj,
                                'field' => $item,
                                'rule' => $rule_value,
                                'msg' => "{$display} has to be a number. Please use a numeric value",
                            ]));
                            break;
                        case 'matches':
                            $mathdisplay = $items[$rule_value]['display'];
                            $obj->runValidation($container->make(MatchesValidator::class, [
                                'model' => $obj,
                                'field' => $item,
                                'rule' => $obj->getEntity()->{'get' . ucwords($obj->getMatchingTestColumn())}(),
                                'msg' => "{$display} does not math {$mathdisplay}",
                            ]));
                            break;
                        case 'unique':
                            $obj->runValidation($container->make(UniqueValidator::class, [
                                'model' => $obj,
                                'field' => $item,
                                'rule' => [$rule_value, $obj->getEntity()->getColID()],
                                'msg' => "This {$display} already exist.",
                            ]));
                            break;
                        }
                    }
                }
            }
        }
    }

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