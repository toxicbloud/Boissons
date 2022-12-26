<?php

namespace boissons\views;

class LoginView
{
  public function __construct($rq)
  {
    $this->rq = $rq;
  }
  public function render()
  {
    $content = $this->html();
    $vue = new View($content, 'Connexion', $this->rq);
    return $vue->getHtml();
  }
  public function html()
  {
    $path = $this->rq->getUri()->getBasePath();
    $info = '';
    switch ($this->rq->getQueryParam('error')) {
      case 'wrongpassword':
        $info = <<<END
        <div id="info" class="text-danger  mb-2">
          mot de passe incorrect
        </div>
        END;
        break;
      case 'wrongusername':
        $info = <<<END
        <div id="info" class="text-danger  mb-2">
            identifiant incorrect
        </div>
        END;
        break;
    }
    $temp = <<<END
        <style>
        body{
            background-image:url('$path/Photos/imageLogin.jpeg');
            background-repeat: no-repeat;
            background-size: 100% 160%;
          }
          @media only screen and (max-device-width: 768px){
            body{
              backdrop-filter: blur(10px);
             }
            }
        </style>
            <div class="container py-5 h-200 mt-5">
              <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="col-12 col-md-8 col-lg-6 col-xl-5">
                  <div class="card shadow-2-strong" style="background-color: rgba(255, 255, 255, .7);">
                    <div class="card-body p-5 text-center">
          
                    <body class="text-center" >
                    <form class="form-signin" action="login" method="post">

                        <h1 class="h3 mb-4" style="font-weight: bold; font-size: 250%; color: rgb(50, 50, 50);">Connexion</h1>

                        <div class="form-outline mb-5">
                          <label for="inputEmail" class="sr-only">Identifiant</label>
                          <input name="pseudo" id="inputEmail" class="form-control" placeholder="Nom d'utilisateur" required="" autofocus="">
                        </div>

                        <div class="form-outline mb-5">
                          <label for="inputPassword" class="sr-only">Password</label>
                          <input name="password" type="password" id="inputPassword" class="form-control" placeholder="Mot de passe" required="">
                          </div>
                          $info
                        <button class="btn btn-lg btn-secondary btn-block" type="submit" style="background-color: rgb(50, 50, 50);">Connexion</button>
                    </form>
                    </body>
          
                    </div>
                  </div>
                </div>
              </div>
            </div>
END;
    return $temp;
  }
}
