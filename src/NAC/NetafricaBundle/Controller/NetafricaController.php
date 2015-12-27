<?php

namespace NAC\NetafricaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class NetafricaController extends Controller
{
    public function indexAction()
	{
		$content = $this->get('templating')->render('NACNetafricaBundle:Netafrica:index.html.twig');
		return new Response($content);
	}
}
