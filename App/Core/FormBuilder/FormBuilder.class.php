<?php

declare(strict_types=1);

class FormBuilder extends AbstractFormBuilder
{
    use FormBuilderTrait;
    use FormalizerTrait;
    use SessionTrait;

    protected array $inputObject = [];
    protected array $htmlAttr = [];
    protected string $html = '';
    protected bool $addCsrf = true;
    protected string $element = '';
    protected string $template;
    protected CoreError $error;
    protected RequestHandler $request;
    protected Token $token;
    protected GlobalVariablesInterface $globals;
    private object|null $dataRepository = null;
    private ContainerInterface $container;
    private FormHelper $formHelper;

    /**
     * Main class constructor.
     *
     * @return void
     */
    public function __construct()
    {
        $this->properties();
    }

    public function __toString() : string
    {
        return sprintf('%s', $this->processform());
    }

    /**
     * Undocumented function.
     *
     * @param array $args
     * @return FormBuilder
     */
    public function form(array $args = []) : self
    {
        if ($args) {
            $this->formAttr = array_merge(self::FORM_PARTS, $args);
        } else {
            $this->formAttr = self::FORM_PARTS;
        }
        if (is_array($this->formAttr)) {
            foreach ($this->formAttr as $key => $value) {
                if (!$this->setAttributes($key, $value)) {
                    $this->setAttributes($key, self::FORM_PARTS[$key]);
                }
            }
        }
        return $this;
    }

    /**
     * This method allows us to chain multiple input types together to build the required
     * form structure.
     *
     * @param array $args - optional argument to modified the values of the input wrapping tag
     * @param null $options
     * @return mixed
     */
    public function add(array $args = [], $options = null, array $settings = []) : self
    {
        if (is_array($args)) {
            foreach ($args as $objectType => $objectTypeOptions) {
                $newTypeObject = $this->container->make($objectType, [
                    'fields' => $objectTypeOptions,
                    'options' => $options,
                    'settings' => $settings,
                ]);
                if (!$newTypeObject instanceof FormBuilderTypeInterface) {
                    throw new FormBuilderInvalidArgumentException('"' . $objectType . '" is not a valid form type object.', 0);
                }
                $newTypeObject->configureOptions();
                $this->inputObject[] = $newTypeObject;
                return $this;
            }
        }
    }

    public function input(array $args = [], $options = null, array $settings = []) : self
    {
        $this->inputObject = [];
        return $this->add($args, $options, $settings)->htmlAttr(array_merge(self::FIELD_ARGS, $this->fieldWrapperClass));
    }

    /**
     * Build Form.
     * ========================================================.
     * @param array $args
     * @return string|bool
     * @throws Exception
     */
    public function build(array $args = []): string|bool
    {
        $this->htmlAttr($args);
        $this->element .= $this->begin();
        $this->element .= PHP_EOL;
        $this->element .= $this->processform();
        $this->element .= PHP_EOL;
        $this->element .= $this->end();
        if (isset($this->element) && !empty($this->element)) {
            return $this->element;
        }
        return false;
    }

    public function htmlAttr(array $args = []) : self
    {
        if ($args) {
            $this->htmlAttr = array_merge(self::HTML_ELEMENT_PARTS, $args);
        } else {
            $this->htmlAttr = self::HTML_ELEMENT_PARTS;
        }
        return $this;
    }

    public function begin(string $alertid = '') : string
    {
        $id = $alertid != '' ? $alertid : 'alertErr';
        $alertHtml = '<div id="' . $id . '"></div>' . PHP_EOL;
        if ($this->addCsrf) {
            $alertHtml .= $this->csrfForm($this->formAttr['id']);
        }
        return sprintf('<form %s>', $this->renderHtmlElement($this->formAttr)) . PHP_EOL . $alertHtml;
    }

    public function processform() : string
    {
        $fields = '';
        if (is_array($this->inputObject)) {
            foreach ($this->inputObject as $objectType) {
                // foreach ((array) $objectType->configureOptions() as $key => $value) {
                //     $this->set($key, $value);
                // }
                $fields .= $this->processFields($objectType);
            }
        }
        return $fields;
    }

    public function end()
    {
        return isset($this->formAttr['leave_form_open']) && $this->formAttr['leave_form_open'] === true ? '' : '</form>';
    }

    /**
     * Instantiate the external csrf fields.
     *
     * @param mixed|null $lock
     * @return string
     * @throws Exception
     */
    public function csrfForm(mixed $lock = null): string
    {
        return $this->token->csrfInput('csrftoken', $lock);
    }

    /**
     * Check the form can be submitted and the request if correct.
     *
     * @param string $submit
     * @return bool
     * @throws Throwable
     */
    public function isFormValid(string $submit): bool
    {
        if ($this->canHandleRequest() && $this->isSubmittable($submit)) {
            return true;
        }
        return false;
    }

    /**
     * Throw an error if the csrf validation fails.
     *
     * @param object $controller
     * @return void
     */
    public function validateCsrf(object $controller)
    {
        if (!$this->csrfValidate()) {
            if (isset($this->error)) {
                $this->error->addError(CoreError::display('err_invalid_csrf'), $controller)->dispatchError();
            }
        }
    }

    /**
     * @return array
     * @throws Throwable
     */
    public function canHandleRequest() : array
    {
        $method = ($this->globals->getServer('REQUEST_METHOD') ?? '');
        if ($method == 'POST' && array_key_exists('HTTP_X_HTTP_METHOD', $_SERVER)) {
            if ($this->globals->getServer('HTTP_X_HTTP_METHOD') == 'DELETE') {
                $method == 'DELETE';
            } elseif ($this->globals->getServer('HTTP_X_HTTP_METHOD') == 'PUT') {
                $method == 'PUT';
            } else {
                throw new FormBuilderUnexpectedValueException('Unexpected Header');
            }
        }
        try {
            return [
                $method,
                (class_exists(RequestHandler::class)) ? $this->request->handler()->request->all() : $_REQUEST,
                $this->getStream(),
            ];
        } catch (Throwable $th) {
            throw $th;
        }
    }

    public function getFormAttr(string $attr)
    {
        if ($attr) {
            $field = $this->request->handler()->get($attr);
            if ($field) {
                return $field;
            }
        }
    }

    /**
     * @throws Throwable
     */
    public function getMethod(string $method) : string
    {
        list($_method, $_post, $_json) = $this->canHandleRequest();
        if ($_method === $method) {
            return $_method;
        }
    }

    /**
     * @throws Throwable
     */
    public function getJson()
    {
        list($_method, $_post, $_json) = $this->canHandleRequest();
        return $_json;
    }

    /**
     * @throws Throwable
     */
    public function getData() : array
    {
        list($_method, $_post, $_json) = $this->canHandleRequest();
        return $_post;
    }

    /**
     * @return bool
     */
    public function isAjax(): bool
    {
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }

    /**
     * Check whether the form is submittable. Submit button should represent
     * the argument name.
     *
     * @param string $name - default to <input type="submit" name="submit">
     * @return bool
     */
    public function isSubmittable(string $name = 'submit') : bool
    {
        $sb = $this->globals->getPost($name);
        return isset($sb);
    }

    /**
     * Wrapper function for validating csrf token.
     *
     * @return bool
     */
    public function csrfValidate(): bool
    {
        // $addCsrf = new AntiCSRF();
        // return $addCsrf->validateRequest();
        return true;
    }

    protected function getStream()
    {
        $ct = $this->globals->getServer('CONTENT_TYPE');
        $contentType = isset($ct) && $this->globals->getServer('REQUEST_METHOD') == 'POST' ? trim($this->globals->getServer('CONTENT_TYPE')) : '';
        if ($contentType === 'application/json') {
            $content = trim(file_get_contents('php://input', false, stream_context_get_default(), 0, $this->globals->getServer('CONTENT_LENGTH')));
            $decode = json_decode($content, true);
            if (is_array($decode)) {
                echo $decode;
            } else {
                throw new FormBuilderUnexpectedValueException('Invalid Data');
            }
        }
    }

    private function properties()
    {
        $this->container = Container::getInstance();
        $this->error = $this->container->make(CoreError::class);
        $this->token = $this->container->make(Token::class);
        $this->request = $this->container->make(RequestHandler::class);
        $this->globals = $this->container->make(GlobalVariablesInterface::class);
        $this->formHelper = $this->container->make(FormHelper::class);
    }

    /**
     * Undocumented function.
     *
     * @param object $objectType
     * @return string
     */
    private function processFields(Object $objectType) : string
    {
        //$html = $container = $before = $after = $show_label = $element_class =  $new_label = '';
        $html = '';
        foreach (self::SUPPORT_INPUT_TYPES as $field) :
            switch ($objectType->getType()) :
                case $field:
                    // [inline_flip_icon, inline_icon, inline_icon_class, before_after_wrapper etc...]
                    extract($objectType->getSettings(), EXTR_SKIP);
        /* Wrap the element and form input within a container element */
        if (isset($template) && $template == true) {
            $html .= (isset($field_wrapper) && $field_wrapper == true) ? $this->buildTemplate($objectType, $this->htmlAttr, $label_up) : '';
            $html = $this->buildLabel($objectType, $html, $this->htmlAttr, $label, $show_label);
            return sprintf($html, $objectType->view($label));
        } else {
            if ($container) {
                //[before, after, element, element_id, element_class, element_style]
                extract($this->htmlAttr);
                $html .= (isset($field_wrapper) && $field_wrapper == true) ? $this->buildTemplate($objectType, $this->htmlAttr, $label_up) : ''; //"\n{$before}" : '';
                $html = $this->buildLabel($objectType, $html, $this->htmlAttr, $label, $show_label);
                $html = sprintf($html, $this->fieldWrapper($html, $element, $element_class, $input_wrapper));
                return sprintf($html, $objectType->view($label));
            } else { /* else we can render the form field outside of a container */
                $html .= $objectType->view();
            }
        }
        break;
        endswitch;
        endforeach;
        return $html;
    }

    private function fieldWrapper(string $template, string $element, array $element_class, bool $wrapper)
    {
        if ($wrapper) {
            $html = '';
            $html .= "\n<{$element}"; // open
            /* Get the element ID */
            $html .= (!empty($element_id) ? ' id="' . $element_id . '"' : '');
            /* Get the element class */
            $html .= (is_array($element_class) && count($element_class) > 0) ? ' class="' . implode(' ', $element_class) . '"' : '';
            /* Get element style if we are using */
            $html .= (!empty($element_style) ? ' style="' . $element_style . '"' : '');
            $html .= '>'; // close
            /* Main element closing tag */
            $html .= "\n %s ";
            $html .= "\n</div>";
            return $html;
        }
        return '%s';
    }
}