<?php

/*
 * This file is part of the Claroline Connect package.
 *
 * (c) Claroline Consortium <consortium@claroline.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Aip\ProfilageBundle;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Claroline\KernelBundle\Bundle\ConfigurationProviderInterface;
use Claroline\CoreBundle\Library\PluginBundle;
use Claroline\KernelBundle\Bundle\ConfigurationBuilder;

class AipProfilageBundle extends PluginBundle

{
     public function hasMigrations()
    {
        return false;
    }
    
   
}

