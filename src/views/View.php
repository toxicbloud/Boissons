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
           <link rel="stylesheet" href="$path/static/styles.css">
           <title> $titre </title>
           
           <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
            <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

           <link href="$path/css/style.css" rel="stylesheet" >
          </head>
        <body>
            <nav class="navbar navbar-expand-lg bg-light">
                <div class="container-fluid">
                    <a class="navbar-brand" href="$path">
                    <img src="$path/Photos/favicon.ico" alt="" width="60" height="60">
                    </a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                            <li class="nav-item dropdown">
                            <!--<a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Dropdown
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#">Action</a></li>
                                <li><a class="dropdown-item" href="#">Another action</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="#">Something else here</a></li>
                            </ul> -->
                            </li>
                            <li class="nav-item">
                            <a class="nav-link" href="$path/cocktails">Cocktails</a>
                            </li>
                        </ul>
                        <div class="navbar-nav justify-content-end">
END;
        if (Authentication::isConnected()) {
            $nom = $_SESSION['user']->username;
            $temp .= <<<END
                        <span class="navbar-brand">$nom</span>
                        <a class="nav-link" href="$path/logout">Deconnexion</a>
END;
        } else {
            $temp .= <<<END
                        <a class="nav-link" href="$path/login">Connexion</a>
                        <a class="nav-link" href="$path/register">Inscription</a>
END;
        }
        $temp .= <<<END
                        </div>
                    </div>
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
