<?php


namespace App\Forms;


use Sirius\controller\Form;
use Sirius\http\Request;
use Sirius\http\Session;

class RegisterForm extends Form
{
    public function __construct(Request $request, object $entity, Session $session, array $options = [])
    {
        parent::__construct($request, $entity, $session, $options);

        $this->addTextInput('username', ['class' => 'form-control', 'placeholder' => "Nom d'utilisateur"]);
        $this->addEmailInput('email', ['required' => true, 'class' => 'form-control', 'placeholder' => 'Email']);
        $this->addPasswordInput('password', ['required' => true, 'class' => 'form-control', 'placeholder' => 'Mot de passe', 'hash' => true]);
        $this->setSubmitValue('accepter', ['class' => 'button-bb-wc']);
    }
}