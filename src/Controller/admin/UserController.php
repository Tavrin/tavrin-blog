<?php

namespace App\Controller\admin;

use App\Entity\User;
use App\Forms\UserEditorForm;
use App\Manager\AdminManager;
use App\Manager\UserManager;
use App\Repository\MediaRepository;
use App\Repository\UserRepository;
use Sirius\controller\Controller;
use Sirius\http\exceptions\NotFoundException;
use Sirius\http\Request;
use Sirius\http\Response;
use Sirius\utils\Paginator;

class UserController extends Controller
{
    public function indexAction(Request $request): Response
    {
        $adminManager = new AdminManager($this->getManager());
        $userRepository = new UserRepository($this->getManager());
        $userData = $this->getManager()->getEntityData('user');
        $paginator = new Paginator();

        if (false === $query = $adminManager->initializeAndValidatePageQuery($request)) {
            $this->redirect($request->getPathInfo() . '?page=1');
        }

        $content = $paginator->paginate($userRepository, $query, 5,'created_at', 'DESC');
        if ($content['actualPage'] > $content['pages']) {
            $this->redirect($request->getPathInfo() . '?page=1');
        }

        $content['items'] = $adminManager->hydrateEntities($content['items'], $userData);

        return $this->render('admin/users/index.html.twig', [
            'content' => $content
        ]);
    }

    /**
     * @throws \Exception
     */
    public function newAction(Request $request): Response
    {
        $user = new User();
        $em = $this->getManager();
        $adminManager = new AdminManager($em);
        $userManager = new UserManager($em);
        $userForm = new UserEditorForm($request,$user, $this->session, ['name' => 'newUser', 'type' => 'new', 'wrapperClass' => 'mb-1', 'isAdmin' => false]);
        $userForm->handle($request);

        if ($userForm->isSubmitted && $userForm->isValid) {
            $media = $userForm->getData('mediaHiddenInput');
            if (isset($media)) {
                $mediaRepository = new MediaRepository($em);
                $user->setMedia($adminManager->findOneByCriteria($mediaRepository, 'path', $media));
            }

            $isAdmin = $userForm->getData('role');
            'true' === $isAdmin ? $user->setRoles(['ROLE_ADMIN']) : $user->setRoles([]);
            $confirmPassword = $userForm->getData('passwordConfirm');
            if (true === $userManager->saveUser($user, $confirmPassword)) {
                $this->redirect('/admin/users?page=1', ['type' => 'success', 'message' => "Utilisateur ajout?? avec succ??s"]);
            }
        } elseif ($userForm->isSubmitted) {
            $this->redirect('/admin/users/new', ['type' => 'danger', 'message' => "L'utilisateur n'a pas pu ??tre ajout??"]);
        }

        $content['action'] = 'new';
        $content['title'] = 'Nouvel utilisateur';

        return $this->render('admin/users/editor.html.twig', [
            'form' => $userForm->renderForm(),
            'content' => $content ?? null
        ]);
    }

    /**
     * @throws \Exception
     */
    function editAction(Request $request, $slug): Response
    {
        $em = $this->getManager();
        $adminManager = new AdminManager($em);
        $userManager = new UserManager($em);

        if (!$user = $adminManager->findOneByCriteria(new UserRepository($em), 'slug', $slug)) {
            throw new NotFoundException('The user doesn\'t exist');
        }

        $userName = $user->getUsername();
        $isAdmin = $user->hasRole('ROLE_ADMIN');
        $userForm = new UserEditorForm($request,$user, $this->session, ['name' => 'newCategory', 'type' => 'edit', 'wrapperClass' => 'mb-1', 'isAdmin' => $isAdmin]);
        $userForm->handle($request);

        if ($userForm->isSubmitted && $userForm->isValid) {
            $media = $userForm->getData('mediaHiddenInput');
            if (isset($media)) {
                $mediaRepository = new MediaRepository($em);
                $user->setMedia($adminManager->findOneByCriteria($mediaRepository, 'path', $media));
            }

            $isAdmin = $userForm->getData('role');
            'true' === $isAdmin ? $user->setRoles(['ROLE_ADMIN']) : $user->setRoles([]);
            if (true === $userManager->updateUser($user, $this->session)) {
                $this->redirect('/admin/users?page=1', ['type' => 'success', 'message' => "Utilisateur {$userName} modifi?? avec succ??s"]);
            } else {
                $this->redirect('/admin/users/' . $slug . '/edit', ['type' => 'danger', 'message' => "L'utilisateur n'a pas pu ??tre modifi??"]);
            }
        } elseif ($userForm->isSubmitted) {
            $this->redirect('/admin/users/' . $slug . '/edit', ['type' => 'danger', 'message' => "L'utilisateur n'a pas pu ??tre modifi??"]);
        }

        $content['action'] = 'edit';
        $content['title'] = '??diter l\'utilisateur : ' . $userName;

        return $this->render('admin/users/editor.html.twig', [
            'form' => $userForm->renderForm(),
            'content' => $content ?? null
        ]);
    }

    public function deleteAction(Request $request, string $slug)
    {
        $redirectPath = $request->getServer('HTTP_REFERER') ?? '/admin/users';
        $adminManager = new AdminManager($this->getManager());
        $userRepository = new UserRepository($this->getManager());
        $adminManager->deleteEntity($userRepository, $slug, 'slug');

        $this->redirect($redirectPath, ['type' => 'success', 'message' => 'Suppression r??ussie']);
    }
}