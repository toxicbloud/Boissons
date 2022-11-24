<?php

namespace boissons\views;

use boissons\controls\Authentication;
use Slim\Http\Request;

class View
{
    private $html;

    /*
     *  $content String correspondant au information de la page à afficher
     *  $titre String le titre qui sera afficher en tant que Title sur la page web
     */
    public function __Construct($content, $titre, Request $rq)
    {
        $page = $this->setTemplate($content, $titre, $rq);
        $this->html = $page;
    }

    public function render()
    {
        $this->gethtml();
    }

    public function setTemplate($content, $titre, Request $rq)
    {
        $path = $rq->getUri()->getBasePath();
        $categories = <<<END
        <li class="nav-item d-flex">
            <a class="nav-link" href="$path/cocktails">Listes publiques</a>
        </li>
    </ul>
    <a class="nav-link" href="$path/login">Connexion</a>
END;
        if (Authentication::isConnected()) {
            $nom = $_SESSION['user']->username;
            $categories = <<<END
                <li class="nav-item d-flex">
                    <a class="nav-link" href="$path/cocktails">Cocktails</a>
                </li>
                <!-- <li class="nav-item d-flex">
                    <a class="nav-link" href="$path/creerliste">Creer votre liste</a>
                </li>
                <li class="nav-item d-flex">
                    <a class="nav-link" href="$path/meslistes">Mes Listes</a>
                </li> -->
            </ul>

            <span class="navbar-brand">$nom</span>
            <a class="nav-link" href="$path/logout">Deconnexion</a>
END;
        }
        $temp = <<<END
         <!DOCTYPE html> <html>
          <head>
           <meta charset="utf-8">
           <meta name="viewport" content="width=device-width, initial-scale=1">
           <meta property="og:title" content="Boissons">
            <meta property="og:type" content="website">
            <meta property="og:url" content="http://boissons.antoninrousseau.fr">
            <meta property="og:image" content="./Photos/icon.png">
            <meta property="og:description" content="Projet Programmation Web L3 Info">
            <meta name="theme-color" content="#F04823">
           <link rel="icon" href="$path/Photos/favicon.ico" />
           <title> $titre </title>
           <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>

           <link href="$path/css/style.css" rel="stylesheet" >
          </head>
        <body>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
	        <a class="navbar-brand" href="$path">
		        <img src="$path/Photos/favicon.ico" alt="" width="60" height="60">
	        </a>
	        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
		        <span class="navbar-toggler-icon"></span>
	        </button>

	        <div class="collapse navbar-collapse" id="navbarSupportedContent">
		        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        $categories

	        </div>
        </nav>
          $content
         </body>
        <html>
END;
        return $temp;
    }

    public function getHtml()
    {
        return $this->html;
    }
}
