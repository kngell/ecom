<?php

declare(strict_types=1);
use ControllerHelper;

class Controller
{
    protected ContainerInterface $container;

    /**
     * @var array
     */
    protected array $middlewares = [];
    protected array $routeParams = [];

    protected string $controller;
    protected string $method;
    protected string $filePath;
    /**
     * Model Suffix.
     *
     * @var string
     */
    protected $modelSuffix = 'Manager';

    public function __construct(protected Token $token, protected MoneyManager $money, protected RequestHandler $request, protected View $view_instance, protected ResponseHandler $response, protected ControllerHelper $helper, protected SessionInterface $session)
    {
    }

    /**
     * Action before and after controller
     * ==========================================================.
     * @param array $arguments
     * @return void
     */
    public function __call(string $name, array $arguments)
    {
        $method = $name . 'Page';
        if (method_exists($this, $method)) {
            if ($this->before() !== false) {
                call_user_func_array([$this, $method], $arguments);
                $this->after();
            }
        } else {
            throw new BaseBadMethodCallException('Method ' . $name . ' does not exist in ' . get_class($this));
        }
    }

    /**
     * Init controller.
     * ==================================================================================================.
     * @param string $controller
     * @param string $method
     * @return self
     */
    public function iniParams(string $controller, string $method, array $rParams, string $path) : self
    {
        $this->controller = $controller;
        $this->method = $method;
        $this->routeParams = $rParams;
        $this->filePath = $path;
        return $this;
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
    public function render(string $viewName, array $context = [])
    {
        if ($this->view_instance === null) {
            throw new BaseLogicException('You cannot use the render method if the View is not available !');
        }
        return $this->view_instance->render($viewName, $context);
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
            return  $this->container->make(View::class)->initParams($this->filePath);
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
        // header('Access-Control-Expose-Headers: Content-Length, X-JSON');
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

    /**
     * Before global conainers
     * ==================================================================================================.
     * @return void
     */
    protected function before()
    {
        $this->view_instance->set_path($this->filePath);
        if ($this->filePath == 'Client' . DS) {
            // $this->view_instance->settings = $this->helper->getSettings();
            // $this->session->set(BRAND_NUM, $this->brand());
            // $data = $this->helper->get_product_and_cart((int) $this->session->get(BRAND_NUM));
            // $this->view_instance->userFrm = $this->container->make(Form::class);
            // $this->view_instance->userFrmAttr = $this->helper->form_params();
            // $this->view_instance->set_siteTitle("K'nGELL Ingénierie Logistique");
            // $this->view_instance->products = $data['products'];
            // $this->view_instance->user_cart = $data['cart'];
            // $this->view_instance->search_box = file_get_contents(FILES . 'template' . DS . 'base' . DS . 'search_box.php');
            // $this->view_instance->productManager = $this->container->make(ProductsManager::class);
        } elseif ($this->filePath == 'Backend' . DS) {
            $this->view_instance->set_siteTitle("K'nGELL Administration");
            $this->view_instance->set_Layout('admin');
        }
    }

    protected function after()
    {
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
}
