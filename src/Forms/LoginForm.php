<?php


namespace App\Forms;


use Sirius\controller\Form;
use Sirius\http\Request;
use Sirius\http\Session;

class LoginForm extends Form
{
    public function __construct(Request $request, object $entity, Session $session, array $options = [])
    {
        parent::__construct($request, $entity, $session, $options);

        $this->addEmailInput('email', ['required' => true, 'class' => 'form-control', 'placeholder' => 'Email'])
            ->addPasswordInput('password', ['required' => true, 'class' => 'form-control', 'placeholder' => 'Mot de passe'])
            ->setSubmitValue('accepter', ['class' => 'button-bb-wc-2 as-c'])
            ->addCss('w-50 w-100-xs d-f fd-c');
    }
}