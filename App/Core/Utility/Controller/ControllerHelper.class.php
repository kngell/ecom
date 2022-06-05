<?php

declare(strict_types=1);

final class ControllerHelper
{
    protected Container $container;

    public function __construct(private Token $token)
    {
    }

    public function showMessage(string $type, mixed $message) : string
    {
        if (is_array($message)) {
            $output = '<div class="align-self-center text-center alert alert-' . $type . ' alert-dismissible">
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"><span class="float-end"></span></button>
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

    public function checkoutSuccessMsg($intentResponse)
    {
        $template = file_get_contents(FILES . 'template' . DS . 'e_commerce' . DS . 'payment' . DS . 'successMessageTemplate.php');
        $template = str_replace('{{transactionID}}', $intentResponse->id, $template);
        $template = str_replace('{{link1}}', PROOT . 'home' . US . 'cart', $template);
        $template = str_replace('{{link2}}', PROOT . 'home' . US . 'boutique', $template);
        return $template;
    }

    public function AssignErrors($source, $errors)
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
}