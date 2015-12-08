<?php

namespace NAC\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class NACUserBundle extends Bundle
{
	public function getParent()
	{
	 return 'FOSUserBundle';
	}
}
