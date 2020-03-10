<?php


namespace App\Controller;

use App\Http\Response;
use App\MySQL\Exceptions\GivenClassNotImplementerITableRowException;
use App\MySQL\Exceptions\QueryException;
use App\Repository\FolderRepository;
use App\Repository\ProductRepository;
use App\Repository\VendorRepository;

class SearchController extends AbstractController
{
    /**
     * @Route(url='/search')
     *
     * @param ProductRepository $product_repository
     * @param VendorRepository $vendor_repository
     * @param FolderRepository $folder_repository
     *
     * @return Response
     * @throws GivenClassNotImplementerITableRowException
     * @throws QueryException
     */
    public function index(ProductRepository $product_repository, VendorRepository $vendor_repository, FolderRepository $folder_repository): Response
    {
        $current_page = $this->request->getIntFromGet('page', 1);
        $per_page = 30;
        $start = $per_page * ($current_page - 1);
        $product_id = $this->request->getIntFromGet('product_id');
        if ($product_id >= 1) {
            $result = $product_repository->find($product_id);
            $results = [
                'count' => $result ? 1 : 0,
                'items' => $result ? array($result) : null,
            ];
        } else {
            $search_data = [
                'name' => $this->request->getStringFromGet('name'),
                'price_from' => $this->request->getFloatFromGet('price_from'),
                'price_to' => $this->request->getFloatFromGet('price_to'),
            ];
            $where_data = $product_repository->getWhereData($search_data);
            $results = [
                'count' => $where_data ? $product_repository->getCount($where_data) : 0,
                'items' => $where_data ? $product_repository->findAllWithLimit($per_page, $start, $where_data) : null,
            ];
        }
        $vendors = $vendor_repository->findAll();
        $folders = $folder_repository->findAll();

        $paginator = [
            'pages' => ceil($results['count'] / $per_page),
            'current' => $current_page,
            'get_params' => preg_replace('/&?page=\d*/','',$_SERVER['QUERY_STRING']),
        ];

        return $this->render('search/index.tpl', [
            'results' => $results,
            'vendors' => $vendors,
            'folders' => $folders,
            'paginator' => $paginator,
        ]);
    }
}