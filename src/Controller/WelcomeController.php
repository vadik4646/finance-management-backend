<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class WelcomeController extends Controller
{
  /**
   * @Route("/", name="welcome")
   */
  public function index()
  {
    return $this->render('base.html.twig');
  }
}
