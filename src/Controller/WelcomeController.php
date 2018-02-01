<?php

namespace App\Controller;

use Psr\Log\LoggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

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
