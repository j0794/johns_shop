<?php


namespace App\Controller;


use App\Http\Response;
use App\MySQL\Exceptions\GivenClassNotImplementerITableRowException;
use App\MySQL\Exceptions\QueryException;
use App\Repository\FolderRepository;

class FolderController extends AbstractController
{
    /**
     * @Route(url='/folder')
     *
     * @param FolderRepository $folder_repository
     *
     * @return Response
     * @throws GivenClassNotImplementerITableRowException
     * @throws QueryException
     */
    public function list(FolderRepository $folder_repository): Response
    {
        $folders = $folder_repository->findAll();
        return $this->render('folder/index.tpl', [
            'folders' => $folders,
        ]);
    }

    /**
     * @Route(url='/folder/edit', url='/folder/edit/{folder_id}')
     *
     * @param FolderRepository $folder_repository
     *
     * @return Response
     * @throws GivenClassNotImplementerITableRowException
     * @throws QueryException
     */
    public function edit(FolderRepository $folder_repository): Response
    {
        $folder_id = $this->getRoute()->getParam('folder_id') ?? 0;
        $folder = $folder_repository->findOrCreate($folder_id);
        return $this->render('folder/edit.tpl', [
            'folder' => $folder,
        ]);
    }

    /**
     * @Route(url='/folder/editing')
     *
     * @param FolderRepository $folder_repository
     *
     * @return Response
     * @throws GivenClassNotImplementerITableRowException
     * @throws QueryException
     */
    public function editing(FolderRepository $folder_repository): Response
    {
        $folder_id = $this->request->getIntFromPost('folder_id');
        $name = $this->request->getStringFromPost('name');
        if (!$name) {
            die('Name required');
        }
        $folder = $folder_repository->findOrCreate($folder_id);
        $folder->setName($name);
        $folder_repository->save($folder);
        return $this->redirectToList();
    }

    /**
     * @Route(url='/folder/delete')
     *
     * @param FolderRepository $folder_repository
     *
     * @return Response
     * @throws GivenClassNotImplementerITableRowException
     * @throws QueryException
     */
    public function delete(FolderRepository $folder_repository): Response
    {
        $folder_id = $this->request->getIntFromPost('folder_id');
        $folder = $folder_repository->find($folder_id);
        $folder_repository->delete($folder);
        return $this->redirectToList();
    }

    /**
     * @return Response
     */
    private function redirectToList(): Response
    {
        return $this->redirect('/folder');
    }
}