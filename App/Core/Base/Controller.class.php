<?php

declare(strict_types=1);

class Controller extends AbstractController
{
    use ControllerTrait;

    protected Token $token;
    protected MoneyManager $money;
    protected RequestHandler $request;
    protected ResponseHandler $response;
    protected ControllerHelper $helper;
    protected SessionInterface $session;
    protected CookieInterface $cookie;
    protected CacheInterface $cache;
    protected LoginForm $loginFrm;
    protected RegisterForm $registerFrm;
    protected ForgotPasswordForm $forgotFrm;
    protected EventDispatcherInterface $dispatcher;
    /**
     * @var array
     */
    protected array $routeParams = [];
    /** @var array */
    protected array $callBeforeMiddlewares = [];
    /** @var array */
    protected array $callAfterMiddlewares = [];
    protected string $controller;
    protected string $method;
    protected string $filePath;
    protected string $modelSuffix = 'Manager';

    public function __construct(array $params = [])
    {
        $this->properties($params);
        $this->createView();
        // $this->diContainer(Yamlfile::get('providers'));
        // $this->initEvents();
        // $this->buildControllerMenu($this->routeParams);
    }

    /** @inheritDoc */
    public function __call($name, $argument) : void
    {
        if (is_string($name) && $name !== '') {
            $method = $name . 'Page';
            if (method_exists($this, $method)) {
                // if ($this->eventDispatcher->hasListeners(BeforeControllerActionEvent::NAME)) {
                //     $this->dispatchEvent(BeforeControllerActionEvent::class, $name, $this->routeParams, $this);
                // }
                if ($this->before() !== false) {
                    call_user_func_array([$this, $method], $argument);
                    $this->after();
                }
            } else {
                throw new BaseBadMethodCallException("Method {$method} does not exists.");
            }
        } else {
            throw new Exception;
        }
    }

    public function render(string $viewName, array $context = []) : ?string
    {
        $this->throwViewException();
        if ($this->view_instance === null) {
            throw new BaseLogicException('You cannot use the render method if the View is not available !');
        }
        return $this->view_instance->render($viewName, $context);
    }

    public function view() : View
    {
        return $this->view_instance;
    }

    public function brand() : int
    {
        switch ($this->controller) {
            case 'ClothingController':
                return 3;
                break;

            default:
                return 2;
                break;
        }
    }

    public function jsonResponse(array $resp) : void
    {
        // $this->response->setHeader();
        // header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS');
        // header('Access-Control-Expose-Headers: Content-Length, X-JSON');
        // header('Access-Control-Allow-Headers: Content-Type, Authorization, Accept, Accept-Language, X-Authorization');
        // header('Access-Control-Max-Age: 86400');
        // http_response_code(200);
        echo json_encode($resp);
        exit;
    }

    public function getController() : string
    {
        return $this->controller;
    }

    public function getMethod() : string
    {
        return $this->method;
    }

    public function redirect(string $url, bool $replace = true, int $responseCode = 303)
    {
        // $this->redirect = new BaseRedirect($url, $this->routeParams, $replace, $responseCode);
        if ($this->redirect) {
            $this->redirect->redirect();
        }
    }

    public function getRoutes(): array
    {
        return $this->routeParams;
    }

    public function getSiteUrl(?string $path = null): string
    {
        return sprintf('%s://%s%s', isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http', $_SERVER['SERVER_NAME'], ($path !== null) ? $path : $_SERVER['REQUEST_URI']);
    }

    public function getCache() : CacheInterface
    {
        return $this->cache;
    }

    public function getSession(): object
    {
        return SessionTrait::sessionFromGlobal();
    }

    protected function callAfterMiddlewares(): array
    {
        return $this->callAfterMiddlewares;
    }

    protected function callBeforeMiddlewares(): array
    {
        return array_merge($this->defineCoreMiddeware(), $this->callBeforeMiddlewares);
    }

    protected function defineCoreMiddeware(): array
    {
        return [
            'error404' => Erorr404::class,
        ];
    }

    protected function createView() : void
    {
        $this->view_instance = $this->container->make(View::class, [
            'viewAry' => [
                'loginFrm' => $this->loginFrm->createForm('security' . DS . 'login'),
                'registerFrm' => $this->registerFrm->createForm('security' . DS . 'register'),
                'forgotFrm' => $this->forgotFrm->createForm('security' . DS . 'forgot'),
                'search_box' => file_get_contents(FILES . 'Template' . DS . 'Base' . DS . 'search_box.php'),
                'token' => $this->token,
                'file_path' => $this->filePath,
                'response' => $this->response,
            ],
        ]);
    }

    protected function before() : void
    {
        // $object = new self($this->routeParams);
        $mdw = ($this->container->make(Middleware::class))->middlewares($this->callBeforeMiddlewares())
            ->middleware($this, function ($object) {
                return $object;
            });
        if ($this->filePath == 'Client' . DS) {
            // $this->view_instance->settings = $this->helper->getSettings();
        // $this->session->set(BRAND_NUM, $this->brand());
            // $data = $this->helper->get_product_and_cart((int) $this->session->get(BRAND_NUM));
        // $this->view_instance->userFrmAttr = $this->helper->form_params();
        // $this->view_instance->set_siteTitle("K'nGELL Ingénierie Logistique");
            // $this->view_instance->products = $data['products'];
            // $this->view_instance->user_cart = $data['cart'];
        // $this->view_instance->productManager = $this->container->make(ProductsManager::class);
        } elseif ($this->filePath == 'Backend' . DS) {
            $this->view_instance->siteTitle("K'nGELL Administration");
            $this->view_instance->layout('admin');
        }
    }

    protected function after() : void
    {
        // $object = new self($this->routeParams);
        ($this->container->make(Middleware::class))->middlewares($this->callAfterMiddlewares())
            ->middleware($this, function ($object) {
                return $object;
            });
    }

    protected function getModelSuffix()
    {
        return $this->modelSuffix;
    }

    protected function get_optionsModel(array $data, Object $m) : mixed
    {
        if (isset($data['tbl_options']) && !empty($data['tbl_options'])) {
            $table_options = json_decode($m->htmlDecode($data['tbl_options']), true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $m = [];
                foreach ($table_options as $tbl_option) {
                    if (!empty($data['tbl_options'])) {
                        $tbl_opt = str_replace(' ', '', ucwords(str_replace('_', ' ', $tbl_option)));
                        $m[$tbl_option] = $this->container->make($tbl_opt . 'Manager'::class);
                    }
                }
                return $m;
            } else {
                if (!empty($data['tbl_options'])) {
                    $tbl_opt = str_replace(' ', '', ucwords(str_replace('_', ' ', $data['tbl_options'])));

                    return $this->container->make($tbl_opt . 'Manager'::class);
                }
            }
        }
        return null;
    }

    protected function model(string $modelString) : Model
    {
        return $this->container->make(ModelFactory::class)->create($modelString);
    }

    /**
     * Init controller.
     * ==================================================================.
     * @param array $params
     * @return self
     */
    private function properties(array $params = []) : self
    {
        if (!empty($params)) {
            foreach ($params as $key => $value) {
                if ($key != '' && property_exists($this, $key)) {
                    $this->{$key} = $value;
                }
            }
        }
        return $this;
    }

    private function throwViewException(): void
    {
        if (null === $this->view_instance) {
            throw new BaseLogicException('You can not use the render method if the build in template engine is not available.');
        }
    }

    private function templateGlobalContext(): array
    {
        return array_merge(['app' => YamlFile::get('app')], ['menu' => YamlFile::get('menu')], ['routes' => (isset($this->routeParams) ? $this->routeParams : [])]);
    }
}