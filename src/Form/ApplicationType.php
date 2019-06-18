<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;


class ApplicationType extends AbstractType{

    /**
     * allow to know input fields
     *
     * @param string $label
     * @param string $placeholder
     * @param array $options
     * @return array
     * 
     */
    protected function getConfig($label, $placeholder, $options =[])
    {
        return array_merge_recursive (["label" =>$label,

            "attr"=>["placeholder" => $placeholder
            ]
        ], $options);
    }
}