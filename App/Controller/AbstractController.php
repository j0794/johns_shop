<?php


namespace App\Controller;


use App\Http\Request;
use App\Http\Response;
use App\Http\ResponseBody\JsonBody;
use App\Http\ResponseBody\TextBody;
use App\Router\Route;

abstract class AbstractController
{
    /**
     * @var Request
     */
    protected $request;

    /**
     * @var Response
     */
    protected $response;

    /**
     * @var \Smarty
     */
    private $smarty;

    /**
     * @var Route
     */
    protected $route;

    /**
     * @var array
     */
    protected $shared_data = [];

    public function __construct(Request $request, Response $response, \Smarty $smarty)
    {
        $this->request = $request;
        $this->response = $response;
        $this->smarty = $smarty;
    }

    /**
     * @return Route
     */
    public function getRoute(): Route
    {
        return $this->route;
    }

    /**
     * @param Route $route
     */
    public function setRoute(Route $route): void
    {
        $this->route = $route;
    }

    /**
     * @param string $key
     * @param $value
     */
    public function addSharedData(string $key, $value) {
        $this->shared_data[$key] = $value;
    }

    /**
     * @param string $template_name
     * @param array $params
     *
     * @return Response
     */
    protected function render(string $template_name, array $params = []): Response
    {
        $this->assignTemplateVariables($this->shared_data);
        $this->assignTemplateVariables($params);
        $body = new TextBody($this->smarty->fetch($template_name));
        $this->response->setBody($body);
        return $this->response;
    }

    /**
     * @param array $data
     */
    private function assignTemplateVariables(array $data)
    {
        if (!$data) {
            return;
        }
        foreach ($data as $key => &$value) {
            if (is_scalar($value)) {
                $this->smarty->assign($key, $value);
            } else {
                $this->smarty->assign_by_ref($key, $value);
            }
        }
    }

    /**
     * @param string $url
     *
     * @return Response
     */
    protected function redirect(string $url): Response
    {
        $this->response->redirect($url);
        return $this->response;
    }

    /**
     * @param array $data_array
     *
     * @return Response
     */
    protected function json(array $data_array): Response
    {
        $body = new JsonBody($data_array);
        $this->response->setBody($body);
        $this->response->setHeader('Content-Type', 'application/json');
        return $this->response;
    }
}