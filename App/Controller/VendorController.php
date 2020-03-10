<?php


namespace App\Controller;


use App\Http\Response;
use App\MySQL\Exceptions\GivenClassNotImplementerITableRowException;
use App\MySQL\Exceptions\QueryException;
use App\Repository\VendorRepository;

class VendorController extends AbstractController
{
    /**
     * @Route(url='/vendor')
     *
     * @param VendorRepository $vendor_repository
     *
     * @return Response
     * @throws GivenClassNotImplementerITableRowException
     * @throws QueryException
     */
    public function list(VendorRepository $vendor_repository): Response
    {
        $vendors = $vendor_repository->findAll();
        return $this->render('vendor/index.tpl', [
            'vendors' => $vendors,
        ]);
    }

    /**
     * @Route(url='/vendor/edit', url='/vendor/edit/{vendor_id}')
     *
     * @param VendorRepository $vendor_repository
     *
     * @return Response
     * @throws GivenClassNotImplementerITableRowException
     * @throws QueryException
     */
    public function edit(VendorRepository $vendor_repository): Response
    {
        $vendor_id = $this->getRoute()->getParam('vendor_id') ?? 0;
        $vendor = $vendor_repository->findOrCreate($vendor_id);
        return $this->render('vendor/edit.tpl', [
            'vendor' => $vendor,
        ]);
    }

    /**
     * @Route(url='/vendor/editing')
     *
     * @param VendorRepository $vendor_repository
     *
     * @return Response
     * @throws GivenClassNotImplementerITableRowException
     * @throws QueryException
     */
    public function editing(VendorRepository $vendor_repository): Response
    {
        $vendor_id = $this->request->getIntFromPost('vendor_id');
        $name = $this->request->getStringFromPost('name');
        if (!$name) {
            die('Name required');
        }
        $vendor = $vendor_repository->findOrCreate($vendor_id);
        $vendor->setName($name);
        $vendor_repository->save($vendor);
        return $this->redirectToList();
    }

    /**
     * @Route(url='/vendor/delete')
     *
     * @param VendorRepository $vendor_repository
     *
     * @return Response
     * @throws GivenClassNotImplementerITableRowException
     * @throws QueryException
     */
    public function delete(VendorRepository $vendor_repository): Response
    {
        $vendor_id = $this->request->getIntFromPost('vendor_id');
        $vendor = $vendor_repository->find($vendor_id);
        $vendor_repository->delete($vendor);
        return $this->redirectToList();
    }

    /**
     * @return Response
     */
    private function redirectToList(): Response
    {
        return $this->redirect('/vendor/');
    }
}