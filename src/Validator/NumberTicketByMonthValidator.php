<?php

namespace App\Validator;


use App\Repository\RequestRepository;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class NumberTicketByMonthValidator extends ConstraintValidator
{


    /**
     * @var RequestRepository
     */
    private $requestRepository;

    public function __construct(RequestRepository $requestRepository)

    {


        $this->requestRepository = $requestRepository;
    }

    public function validate($object, Constraint $constraint)
    {
        /* @var $constraint \App\Validator\NumberTicketByMonth */

//        if (null === $value || '' === $value) {
//            return;
//        }
        $date=new \DateTime();
        $month=date('m',time());
        dd($month);

        $this->context->buildViolation($constraint->message)
            ->setParameter('{{ value }}', $value)
            ->addViolation();
    }
}
