<?php

namespace App\Forms;

use Illuminate\Validation\Rules\Password;
use Kris\LaravelFormBuilder\Form;

class UserForm extends Form
{
    protected $clientValidationEnabled = false;

    /**
     * Formulaire de gestion des utilisateurs
     *
     * @return \Kris\LaravelFormBuilder\Form
     */
    public function buildForm()
    {
        $this->add(
            'name',
            'text',
            [
                'label' => "Nom d'utilisateur",
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => "Entrez le nom de l'utilisateur ici"
                ],
                'rules' => [
                    'required',
                    'string',
                ],
                'error_messages' => [
                    'name.required' => "Le nom de l'utilisateur est requis.",
                    'name.string' => "Le nom de l'utilisateur doit etre une chaine de charactères.",
                ]
            ]
        )->add(
            'email',
            'email',
            [
                'label' => "Adresse E-mail de l'utilisateur",
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => "Entrez l'adresse E-mail de l'utilisateur ici",
                ],
                'rules' => [
                    'required',
                    'string',
                    'email',
                    'unique:users',
                ],
                'error_messages' => [
                    'email.required' => "Adresse E-mail requise.",
                    'email.string' => "L'adresse E-mail doit etre une chaine de charactères.",
                    'email.email' => "Le format de l'adresse E-mail n'est pas valide.",
                    'email.unique' => "Un utilisateur possède deja cet email, veuillez le changer.",
                ]
            ]
        )->add(
            'phone',
            'text',
            [
                'label' => "Numéro de téléphone de l'utilisateur",
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => "Entrez le numéro de téléphone de l'utilisateur ici",
                ],
                'rules' => [
                    'required',
                    'min:9',
                    'max:15',
                    'numeric',
                    'unique:users',
                ],
                'error_messages' => [
                    'phone.required' => "Numéro de téléphone requis.",
                    'phone.numeric' => "Le numéro de téléphone doit etre une suite de chiffres.",
                    'phone.min' => "Le numéro de téléphone doit avoir au moins 9 chiffres.",
                    'phone.max' => "Le numéro de téléphone doit avoir au plus 15 chiffres.",
                    'phone.unique' => "Un utilisateur possède deja cet numéro de télé^hone, veuillez le changer.",
                ]
            ]
        )->add(
            'password',
            'password',
            [
                'label' => "Mot de passe de l'utilisateur",
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => "Entrez le mot de passe de l'utilisateur ici"
                ],
                'rules' => [
                    Password::min(8)
                        ->letters()
                        ->numbers()
                        ->mixedCase()
                        ->symbols()
                ]
            ]
        )->add(
            'rule',
            'choice',
            [
                'label' => "Role de l'utilisateur",
                'attr' => [
                    'class' => 'form-control'
                ],
                'choices' => [
                    'admin' => 'Admin',
                    'owner' => 'Propriétaire',
                ],
                'default_value' => 'owner'
            ]
        )->add(
            'submit',
            'submit',
            [
                'label' => empty($this->getModel()->id) ? "Créer" : "Modifier",
                'attr' => [
                    'class' => 'btn btn-primary'
                ]
            ]
        );

        if ($this->getModel() && $this->getModel()->id) {
            $url = route('user_update', $this->getModel()->id);
            $this->modify('email', 'email', [
                'label' => "Adresse E-mail de l'utilisateur",
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => "Entrez l'adresse E-mail de l'utilisateur ici",
                ],
                'rules' => [
                    'required',
                    'string',
                    'email',
                ],
                'error_messages' => [
                    'email.required' => "Adresse E-mail requise.",
                    'email.string' => "L'adresse E-mail doit etre une chaine de charactères.",
                    'email.email' => "Le format de l'adresse E-mail n'est pas valide.",
                ]
            ], true);
            $this->modify('phone', 'text', [
                'label' => "Numéro de téléphone de l'utilisateur",
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => "Entrez le numéro de téléphone de l'utilisateur ici",
                ],
                'rules' => [
                    'required',
                    'min:9',
                    'max:15',
                    'numeric',
                ],
                'error_messages' => [
                    'phone.required' => "Numéro de téléphone requis.",
                    'phone.numeric' => "Le numéro de téléphone doit etre une suite de chiffres.",
                    'phone.min' => "Le numéro de téléphone doit avoir au moins 9 chiffres.",
                    'phone.max' => "Le numéro de téléphone doit avoir au plus 15 chiffres.",
                ]
            ], true);
            empty($this->getModel()->deleted_at) ?: $this->remove('submit');
            $this->remove('password');
        } else {
            $url = route('user_store');
            $this->addAfter(
                'submit',
                'reset',
                'reset',
                [
                    'label' => 'Reset',
                    'attr' => [
                        'class' => 'btn btn-danger'
                    ]
                ]
            );
        }

        $this->formOptions = [
            'method' => empty($this->getModel()->id) ? "POST" : "PUT",
            'url' => $url,
        ];
    }
}
