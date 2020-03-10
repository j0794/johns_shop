<?php


namespace App\Controller;


use App\Http\Response;
use App\Model\User;
use App\MySQL\Exceptions\GivenClassNotImplementerITableRowException;
use App\MySQL\Exceptions\QueryException;
use App\Repository\UserRepository;
use App\Service\UserService;

class UserController extends AbstractController
{
    /**
     * @Route(url='/user/login')
     *
     * @param UserRepository $user_repository
     * @param UserService $user_service
     *
     * @return Response
     */
    public function login(UserRepository $user_repository, UserService $user_service): Response
    {
        $login = $this->request->getStringFromPost('login');
        $password = $this->request->getStringFromPost('password');

        /**
         * @var $user User
         */
        $user = $user_repository->findByName($login);

        $error_msg = 'User not found or data is incorrect';

        if (is_null($user)) {
            echo $error_msg;
            exit;
        }

        $password = $user_service->generatePasswordHash($password);

        if ($user->getPassword() !== $password) {
            echo $error_msg;
            exit;
        }

        $_SESSION['user_id'] = $user->getId();

        return $this->redirect('/');
    }

    /**
     * @Route(url='/user/logout')
     *
     * @return Response
     */
    public function logout(): Response
    {
        unset($_SESSION['user_id']);
        return $this->redirect('/');
    }

    /**
     * @Route(url='/user/edit')
     *
     * @return Response
     */
    public function edit(): Response
    {
        return $this->render('user/edit.tpl');
    }

    /**
     * @Route(url='/user/editing')
     *
     * @param UserRepository $user_repository
     * @param UserService $user_service
     *
     * @return Response
     * @throws GivenClassNotImplementerITableRowException
     * @throws QueryException
     */
    public function editing(UserRepository $user_repository, UserService $user_service): Response
    {
        $user = $user_service->getCurrentUser();
        $name = $this->request->getStringFromPost('name');
        $email = $this->request->getStringFromPost('email');
        $password = $this->request->getStringFromPost('password');
        $password_repeat = $this->request->getStringFromPost('password_repeat');
        if ($password !== $password_repeat) {
            die('Passwords mismatch');
        }
        $is_email_exist = $user_repository->isEmailExist($email);
        if ($is_email_exist) {
            die('Email is busy');
        }
        $password = $user_service->generatePasswordHash($password);
        $user->setEmail($email);
        $user->setName($name);
        $user->setPassword($password);
        if (!$user->getId()) {
            mail($email, 'Ура, вы успешно зарегистрировались', 'Вы зарегистрировались как: ' . $name);
        }
        $user_repository->save($user);
        return $this->redirect('/');
    }
}