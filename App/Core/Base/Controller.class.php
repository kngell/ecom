<?php

declare(strict_types=1);

class Controller extends AbstractController implements ControllerInterface
{
    protected ContainerInterface $container;
    protected Token $token;
    protected MoneyManager $money;
    protected RequestHandler $request;
    protected View $view_instance;
    protected ResponseHandler $response;
    protected ControllerHelper $helper;
    protected SessionInterface $session;
    protected CookieInterface $cookie;
    protected CacheInterface $cache;
    protected LoginForm $loginFrm;
    protected RegisterForm $registerFrm;
    protected DispatcherInterface $dispatcher;
    /**
     * @var array
     */
    protected array $middlewares = [];
    protected array $routeParams = [];

    /** @var array */
    protected array $callBeforeMiddlewares = [];
    /** @var array */
    protected array $callAfterMiddlewares = [];

    protected string $controller;
    protected string $method;
    protected string $filePath;
    /**
     * Model Suffix.
     *
     * @var string
     */
    protected $modelSuffix = 'Manager';

    public function __construct(array $params = [])
    {
        $this->properties($params);
        $this->createView();
        // $this->diContainer(Yamlfile::get('providers'));
        // $this->initEvents();
        // $this->buildControllerMenu($this->routeParams);
    }

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
    /**
     * Register Controller Middlewares
     * ==================================================================================================.
     * @param BaseMiddleWare $middleware
     * @return void
     */
    // public function registerMiddleware(BaseMiddleWare $middleware) : void
    // {
    //     $this->middlewares[] = $middleware;
    // }

    /**
     * Get Middlewares
     * ==================================================================================================.
     * @return array
     */
    public function getMiddlewares() : array
    {
        return $this->middlewares;
    }

    /**
     * Render View client side
     *  =========================================================.
     * @param string $viewName
     * @param array $context
     * @return void
     */
    public function render(string $viewName, array $context = []) : void
    {
        if ($this->view_instance === null) {
            throw new BaseLogicException('You cannot use the render method if the View is not available !');
        }
        $this->view_instance->render($viewName, $context);
    }

    public function resetView() : self
    {
        $this->view_instance->reset();
        return $this;
    }

    public function siteTitle(?string $title = null)
    {
        if ($this->view_instance === null) {
            throw new BaseLogicException('You cannot use the render method if the View is not available !');
        }
        return $this->view_instance->siteTitle($title);
    }

    public function pageTitle(?string $page = null)
    {
        if ($this->view_instance === null) {
            throw new BaseLogicException('You cannot use the render method if the View is not available !');
        }
        return $this->view_instance->pageTitle($page);
    }

    /**
     * Get Viw
     * ==================================================================================================.
     * @return View
     */
    public function getView() : View
    {
        if (!isset($this->view_instance)) {
            $this->filePath = !isset($this->filePath) ? $this->container->make('ControllerPath') : $this->filePath;
            return  $this->view_instance->initParams($this->filePath);
        }
        return $this->view_instance;
    }

    /**
     * Get Container.
     * ==================================================================================================.
     * @return void
     */
    public function get_container()
    {
        return $this->container;
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

    /**
     * Set Controller path
     * ==================================================================================================.
     * @param string $path
     * @return self
     */
    public function set_path(string $path) : self
    {
        if (!isset($this->filePath)) {
            $this->filePath = $path;
        }
        return $this;
    }

    public function jsonResponse(array $resp)
    {
        // $this->response->setHeader();
        // header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS');
        header('Access-Control-Expose-Headers: Content-Length, X-JSON');
        // header('Access-Control-Allow-Headers: Content-Type, Authorization, Accept, Accept-Language, X-Authorization');
        // header('Access-Control-Max-Age: 86400');
        // http_response_code(200);
        echo json_encode($resp);
        exit;
    }

    public function get_controller()
    {
        return $this->controller;
    }

    public function get_method()
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

    // public function view(string $template, array $context = [])
    // {
    //     $this->throwViewException();
    //     $templateContext = array_merge($this->templateGlobalContext(), $this->templateModelContext());
    //     if ($this->eventDispatcher->hasListeners(BeforeRenderActionEvent::NAME)) {
    //         $this->dispatchEvent(BeforeRenderActionEvent::class, $this->method, $templateContext, $this);
    //     }
    //     $response = $this->response->handler();
    //     $request = $this->request->handler();
    //     $response->setCharset('ISO-8859-1');
    //     $response->headers->set('Content-Type', 'text/plain');
    //     $response->setStatusCode($response::HTTP_OK);
    //     $response->setContent($this->templateEngine->ashRender($template, array_merge($context, $templateContext)));
    //     if ($response->isNotModified($request)) {
    //         $response->prepare($request);
    //         $response->send();
    //     }
    // }

    public function getRoutes(): array
    {
        return $this->routeParams;
    }

    public function onSelf()
    {
        if (isset($_SERVER['REQUEST_URI'])) {
            return$_SERVER['REQUEST_URI'];
        }
    }

    public function getSiteUrl(?string $path = null): string
    {
        return sprintf('%s://%s%s', isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http', $_SERVER['SERVER_NAME'], ($path !== null) ? $path : $_SERVER['REQUEST_URI']);
    }

    public function flashAndRedirect(bool $action, ?string $redirect, string $message, string $type = FlashType::SUCCESS): void
    {
        if (is_bool($action)) {
            $this->flashMessage($message, $type);
            $this->redirect(($redirect === null) ? $this->onSelf() : $redirect);
        }
    }

    public function flashMessage(string $message, string $type = FlashType::SUCCESS)
    {
        $flash = (new Flash(SessionTrait::sessionFromGlobal()))->add($message, $type);
        if ($flash) {
            return $flash;
        }
    }

    public function flashWarning(): string
    {
        return FlashType::WARNING;
    }

    public function flashSuccess(): string
    {
        return FlashType::SUCCESS;
    }

    public function flashDanger(): string
    {
        return FlashType::DANGER;
    }

    public function flashInfo(): string
    {
        return FlashType::INFO;
    }

    public function locale(?string $locale = null): ?string
    {
        /*if (null !== $locale)
            return Translation::getInstance()->$locale;*/
        return $locale;
    }

    public function getCache()
    {
        return $this->cache();
    }

    public function cache(): object
    {
        return Application::getInstance()->loadCache();
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
        $this->view_instance = $this->container->make(ViewInterface::class, [
            'viewAry' => [
                'loginFrm' => $this->loginFrm->createForm('security' . DS . 'login'),
                'registerFrm' => $this->registerFrm->createForm('security' . DS . 'login'),
                'search_box' => file_get_contents(FILES . 'Template' . DS . 'Base' . DS . 'search_box.php'),
                'token' => $this->token,
                'file_path' => $this->filePath,
                'response' => $this->response,
            ],
        ]);
    }

    /**
     * Before global conainers
     * ==================================================================================================.
     * @return void
     */
    protected function before()
    {
        // $object = new self($this->routeParams);
        $mdw = ($this->container->make(Middleware::class))->middlewares($this->callBeforeMiddlewares())
            ->middleware($this, function ($object) {
                return $object;
            });
        if ($this->filePath == 'Client' . DS) {
            $this->dispatcher->add(name:NullEvent::class, listeners:[NullListener::class]);
            $this->dispatcher->remove(name:NullEvent::class, listener:NullListener::class);
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

    protected function after()
    {
        // $object = new self($this->routeParams);
        ($this->container->make(Middleware::class))->middlewares($this->callAfterMiddlewares())
            ->middleware($this, function ($object) {
                return $object;
            });
    }

    protected function open_userCheckoutSession()
    {
        $user_data = $this->view_instance->user_data;
        $shipping = current(array_filter($this->view_instance->shipping_class->get_results(), function ($shipping) {
            return $shipping->default_shipping_class == '1';
        }));
        $userCheckoutSession = [
            'cart_items' => array_column($this->view_instance->user_cart[0], 'cart_id'),
            'email' => $user_data->email,
            'ship_address' => [
                'id' => $user_data->abID,
                'name' => $this->request->htmlDecode($user_data->address1 ?? '') . ' ' . $this->request->htmlDecode($user_data->address2 ?? '') . ', ' . $this->request->htmlDecode($user_data->zip_code ?? '') . ', ' . $this->request->htmlDecode($user_data->ville ?? '') . ' (' . $this->request->htmlDecode($user_data->region ?? '') . ') - ' . $this->request->htmlDecode($user_data->pays ?? ''),
            ],
            'bill_address' => [
                'id' => $user_data->abID,
                'name' => $this->request->htmlDecode($user_data->address1 ?? '') . ' ' . $this->request->htmlDecode($user_data->address2 ?? '') . ', ' . $this->request->htmlDecode($user_data->zip_code ?? '') . ', ' . $this->request->htmlDecode($user_data->ville ?? '') . ' (' . $this->request->htmlDecode($user_data->region ?? '') . ') - ' . $this->request->htmlDecode($user_data->pays ?? ''),
            ],
            'shipping' => [
                'id' => $shipping->shcID,
                'price' => $shipping->price,
                'name' => $shipping->sh_name,
            ],
            'ttc' => $this->view_instance->user_cart[2][1],
        ];
        $this->session->set(CHECKOUT_PROCESS_NAME, $userCheckoutSession);
    }

    protected function getModelSuffix()
    {
        return $this->modelSuffix;
    }

    /**
     * Get Options Model
     * ==================================================================================================.
     * @param array $data
     * @param object $m
     * @return mixed
     */
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

    protected function model(string $modelString)
    {
        return $this->container->make(ModelFactory::class)->create($modelString);
    }
    /**
     * Action before and after controller
     * ==========================================================.
     * @param array $arguments
     * @return void
     */
    // public function __call(string $name, array $arguments)
    // {
    //     $method = $name . 'Page';
    //     if (method_exists($this, $method)) {
    //         if ($this->before() !== false) {
    //             call_user_func_array([$this, $method], $arguments);
    //             $this->after();
    //         }
    //     } else {
    //         throw new BaseBadMethodCallException('Method ' . $name . ' does not exist in ' . get_class($this));
    //     }
    // }

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

    // private function templateModelContext(): array
    // {
    //     if (!class_exists(UserModel::class) || !class_exists(PermissionModel::class)) {
    //         return [];
    //     }
    //     return array_merge(['current_user' => Authorized::grantedUser()], ['privilege_user' => PrivilegedUser::getUser()], ['func' => new TemplateExtension($this)], );
    // }
}