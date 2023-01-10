<?php

namespace App\Http\Library;

trait ProductLibrary
{
    use Library;

    /**
     * Validation Rules To Be Used When Creating a Product
     * @return string[][]
     */
    protected function productValidatedRules()
    {
        return [
            'name' => ['required', 'string', 'max:50'],
            'description' => ['required', 'string', 'max:255'],
            'quantity' => ['required', 'numeric'],
            'price' => ['required', 'numeric'],
        ];
    }

    /**
     * Contraintes de validation des attributs d'un produit
     * @return string[]
     */
    protected function productMessagesError()
    {
        return [
            "name.required" => "Le nom du produit est requis!",
            "name.string" => "Le nom du produit doit etre une chaine de charactères!",
            "name.max" => "Le nom du produit doit avoir une longueur max de {{limit}} charactères!",

            "description.required" => "La description du produit est requise!",
            "description.string" => "La description doit etre une chaine de charactères!",
            "description.max" => "La description doit avoir une longueur max de {limit} charactères!",

            "quantity.required" => "La quantité est requise!",
            "quantity.numeric" => "La quantité ne doit contenir que des chiffres!",

            "price.required" => "Le prix est requis!",
            "price.numeric" => "Le prix ne doit contenir que des chiffres!",
        ];
    }
}
