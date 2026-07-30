<?php

namespace App\Model\Security;

use App\Model\Entity\User;

/**
 * @method User getIdentity()
 */
class SecurityUser extends \ADT\DoctrineAuthenticator\SecurityUser
{

}
