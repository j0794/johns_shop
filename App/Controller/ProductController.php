<?php


namespace App\Controller;

use App\Http\Response;
use App\Model\Product;
use App\MySQL\Exceptions\GivenClassNotImplementerITableRowException;
use App\MySQL\Exceptions\QueryException;
use App\Repository\FolderRepository;
use App\Repository\ProductRepository;
use App\Repository\VendorRepository;
use App\Service\CartService;
use App\Service\UserService;

class ProductController extends AbstractController
{
    /**
     * @Route(url='/product/buy/{product_id}')
     *
     * @param ProductRepository $product_repository
     * @param CartService $cart_service
     *
     * @return Response
     * @throws GivenClassNotImplementerITableRowException
     * @throws QueryException
     */
    public function buy(ProductRepository $product_repository, CartService $cart_service): Response
    {
        $product_id = $this->getRoute()->getParam('product_id') ?? 0;
        $product = $product_repository->find($product_id);
        $cart_service->addProduct($product);
        return $this->redirect($_SERVER['HTTP_REFERER']);
    }

    /**
     * @Route(url='/', url='/product/list')
     *
     * @param ProductRepository $product_repository
     * @param VendorRepository $vendor_repository
     * @param FolderRepository $folder_repository
     *
     * @return Response
     * @throws GivenClassNotImplementerITableRowException
     * @throws QueryException
     */
    public function list(ProductRepository $product_repository, VendorRepository $vendor_repository, FolderRepository $folder_repository): Response
    {
        $current_page = $this->request->getIntFromGet('page', 1);
        $per_page = 30;
        $start = $per_page * ($current_page - 1);
        $products = [
            'count' => $product_repository->getCount(),
            'items' => $product_repository->findAllWithLimit($per_page, $start),
        ];
        $vendors = $vendor_repository->findAll();
        $folders = $folder_repository->findAll();
        $paginator = [
            'pages' => ceil($products['count'] / $per_page),
            'current' => $current_page
        ];
        return $this->render('index.tpl', [
            'products' => $products,
            'vendors' => $vendors,
            'folders' => $folders,
            'paginator' => $paginator,
        ]);
    }

    /**
     * @Route(url='/product/view/{product_id}')
     *
     * @param ProductRepository $product_repository
     * @param VendorRepository $vendor_repository
     * @param FolderRepository $folder_repository
     *
     * @return Response
     * @throws GivenClassNotImplementerITableRowException
     * @throws QueryException
     */
    public function view(ProductRepository $product_repository, VendorRepository $vendor_repository, FolderRepository $folder_repository): Response
    {
        $product_id = $this->getRoute()->getParam('product_id') ?? 0;
        $product = $product_repository->find($product_id);
        $vendors = $vendor_repository->findAll();
        $folders = $folder_repository->findAll();
        return $this->render('product/view.tpl', [
            'product' => $product,
            'vendors' => $vendors,
            'folders' => $folders,
        ]);
    }

    /**
     * @Route(url='/product/edit', url='/product/edit/{product_id}')
     *
     * @param ProductRepository $product_repository
     * @param VendorRepository $vendor_repository
     * @param FolderRepository $folder_repository
     * @param UserService $user_service
     *
     * @return Response
     * @throws GivenClassNotImplementerITableRowException
     * @throws QueryException
     */
    public function edit(ProductRepository $product_repository, VendorRepository $vendor_repository, FolderRepository $folder_repository, UserService $user_service): Response
    {
        $user = $user_service->getCurrentUser();
        if (!$user->getId()) {
            die('Access denied');
        }
        $product_id = $this->getRoute()->getParam('product_id') ?? 0;
        $product = $product_repository->findOrCreate($product_id);
        $vendors = $vendor_repository->findAll();
        $folders = $folder_repository->findAll();
        return $this->render('product/edit.tpl', [
            'product' => $product,
            'vendors' => $vendors,
            'folders' => $folders,
        ]);
    }

    /**
     * @Route(url='/product/editing')
     *
     * @param ProductRepository $product_repository
     * @param UserService $user_service
     *
     * @return Response
     * @throws GivenClassNotImplementerITableRowException
     * @throws QueryException
     */
    public function editing(ProductRepository $product_repository, UserService $user_service): Response
    {
        $user = $user_service->getCurrentUser();
        if (!$user->getId()) {
            die('Access denied');
        }
        $product_id = $this->request->getIntFromPost('product_id');
        $name = $this->request->getStringFromPost('name');
        $price = $this->request->getFloatFromPost('price');
        $amount = $this->request->getIntFromPost('amount');
        $description = $this->request->getStringFromPost('description');
        $vendor_id = $this->request->getIntFromPost('vendor_id');
        $folder_ids = $this->request->getArrayFromPost('folder_ids');
        if (!$name || !$price || !$amount) {
            die('Not enough data');
        }
        /**
         * @var Product $product
         */
        $product = $product_repository->findOrCreate($product_id);
        $product->setName($name);
        $product->setPrice($price);
        $product->setAmount($amount);
        $product->setDescription($description);
        $product->setVendorId($vendor_id);
        $product->removeAllFolders();
        foreach ($folder_ids as $folder_id) {
            $product->addFolderId($folder_id);
        }
        $product_repository->save($product);
        return $this->redirect('/');
    }
}