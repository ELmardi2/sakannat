<?php

namespace App\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

/**
 * undocumented class
 */
class FrenchToDateTimeTransformer  implements DataTransformerInterface
{
    public function transform($date){
        if ($date === null) {
            return '';
        }
        return $date->format('d/m/Y');
    }

    public function reverseTransform($frenchDate)
    {
        //French date 06/02/2019
        if ($frenchDate === null) {
            //exception........
            throw new TransformationFailedException("Provide Date please");
            
        }
        $date = \DateTime::createFromFormat('d/m/Y', $frenchDate);
        if ($date === false) {
            throw new TransformationFailedException("Provide a valid date please");
        }
        return $date;
    }
}
