<?php
/**
 * Created by PhpStorm.
 * User: Pavel_Burylichau
 * Date: 2/27/2019
 * Time: 1:26 AM
 */

namespace App\Controller;


use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Class BaseController
 * @package App\Controller
 */
abstract class BaseController extends AbstractController
{
    protected function getUser(): User
    {
        return parent::getUser();
    }
}
