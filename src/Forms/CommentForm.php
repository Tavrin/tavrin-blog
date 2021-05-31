<?php


namespace App\Forms;


use Sirius\http\Request;
use Sirius\http\Session;

class CommentForm extends \Sirius\controller\Form
{
    public function __construct(Request $request, object $entity, Session $session, array $options = [])
    {
        parent::__construct($request, $entity, $session, $options);

        $this->addCss('w-100 mt-2')
            ->addTextareaInput('Commentaire',['class' => 'form-control', 'entity' => false, 'placeholder' => "Commentaire", 'value' => '', 'label' => 'Poster un commentaire', 'rows' => 5, 'cols' => 35])
            ->setSubmitValue('Poster', ['class' => 'button-bb-wc ta-c'])
        ;

    }
}