<?php

namespace App\Forms;

use Kris\LaravelFormBuilder\Form;

class ProductForm extends Form
{
    protected $clientValidationEnabled = false;

    /**
     * Formulaire de gestion des produits
     *
     * @return \Kris\LaravelFormBuilder\Form
     */
    public function buildForm()
    {
        $this
            ->add(
                'name',
                'text',
                [
                    'label' => 'Nom du produit',
                    'attr' => [
                        'class' => 'form-control',
                        'placeholder' => 'Nom produit'
                    ],
                    'rules' => [
                        'required',
                    ]
                ]
            )
            ->add(
                'description',
                'textarea',
                [
                    'label' => 'Description du produit',
                    'attr' => [
                        'class' => 'form-control',
                        'placeholder' => 'Description produit'
                    ],
                    'rules' => [
                        'required',
                    ]
                ]
            )
            ->add(
                'quantity',
                'text',
                [
                    'label' => 'Quantité',
                    'attr' => [
                        'class' => 'form-control',
                        'placeholder' => 'Quantité'
                    ],
                    'rules' => [
                        'required',
                        'numeric',
                    ]
                ]
            )
            ->add(
                'price',
                'text',
                [
                    'label' => 'Prix du produit',
                    'attr' => [
                        'class' => 'form-control',
                        'placeholder' => 'Prix produit'
                    ],
                    'rules' => [
                        'required',
                        'numeric',
                    ]
                ]
            )
            ->add(
                'image',
                'file',
                [
                    'label' => 'Image du produit',
                    'attr' => [
                        'class' => 'form-control',
                        // 'accept' => "image/jpg, image/jpeg, image/png",
                    ],
                    'rules' => [
                        'mimes:jpg,jpeg,png',
                        'max:2048'
                    ]
                ]
            )
            ->add(
                'published',
                'choice',
                [
                    'label' => 'Publier le produit',
                    'attr' => [
                        'class' => 'form-control',
                    ],
                    'choices' => [
                        true => 'Oui',
                        false => 'Non',
                    ],
                    'default_value' => false
                ]
            )
            ->add(
                'submit',
                'submit',
                [
                    'label' => empty($this->getModel()->id) ? "Créer" : "Modifier",
                    'attr' => [
                        'class' => 'btn btn-primary',
                    ]
                ]
            );

        if ($this->getModel() && $this->getModel()->id) {
            $url = route('product_update', $this->getModel()->id);
            empty($this->getModel()->deleted_at) ?: $this->remove('submit');
        } else {
            $url = route('product_store');
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
            'enctype' => 'multipart/form-data'
        ];
    }
}
