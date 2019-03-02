<?php
/**
 * Created by PhpStorm.
 * User: Pavel_Burylichau
 * Date: 3/2/2019
 * Time: 11:48 PM
 */

namespace App\Form\Model;


use App\Validator\UniqueUser;
use Symfony\Component\Validator\Constraints as Assert;

class UserRegistrationFormModel
{
    /**
     * @UniqueUser(message="You are not a unique user.")
     * @Assert\NotBlank(message="Please enter an email.")
     * @Assert\Email()
     */
    public $email;

    /**
     * @Assert\NotBlank(message="Fill a password.")
     * @Assert\Length(min="5", minMessage="Need more symbols!")
     */
    public $plainPassword;

    /**
     * @Assert\IsTrue(message="You have to accept this.")
     */
    public $agreeTerms;
}
