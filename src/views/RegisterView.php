<?php 

namespace boissons\views;

class RegisterView
{
    private $error;
    public function __construct($rq, $error = NULL)
    {
        $this->rq = $rq;
        $this->error = $error;
    }
    public function render()
    {
        $content = $this->html();
        $vue = new View($content, 'Inscription', $this->rq);
        return $vue->getHtml();
    }
    public function html()
    {
        $path = $this->rq->getUri()->getBasePath();
        $temp = <<<END
        <style> .card-registration .select-input.form-control[readonly]:not([disabled]) {
            font-size: 1rem;
            line-height: 2.15;
            padding-left: .75em;
            padding-right: .75em;
            }
            .card-registration .select-arrow {
            top: 13px;
            }
        </style>
        <section class="h-100 bg-dark">
        <form action="register" method="post">
        <div class="container py-5 h-100">
            <div class="row d-flex justify-content-center align-items-center h-100">
            <div class="col">
                <div class="card card-registration my-4">
                <div class="row g-0">
                    <div class="col-xl-6 d-none d-xl-block">
                    <img src="$path/Photos/imageRegister.png"
                        alt="Sample photo" class="img-fluid"
                        style="border-top-left-radius: .25rem; border-bottom-left-radius: .25rem;" />
                    </div>
                    <div class="col-xl-6">
                    <div class="card-body p-md-5 text-black">
                        <h3 class="mb-5 text-uppercase">Inscription</h3>

                        <div class="row">
                        <div class="col-md-6 mb-4">
                            <div class="form-outline">
                            <input type="text" id="firstnameText" class="form-control form-control-lg" />
                            <label class="form-label" for="firstnameText">Prénom</label>
                            </div>
                        </div>
                        <div class="col-md-6 mb-4">
                            <div class="form-outline">
                            <input type="text" id="lastnameText" class="form-control form-control-lg" />
                            <label class="form-label" for="lastnameText">Nom</label>
                            </div>
                        </div>
                        </div>

                        <div class="form-outline mb-4">
                        <input type="text" id="form3Example8" class="form-control form-control-lg" />
                        <label class="form-label" for="form3Example8">Addresse</label>
                        </div>

                        <div class="row">
                        <div class="col-md-6 mb-4">
                            <div class="form-outline">
                            <input type="text" id="form3Example1m" class="form-control form-control-lg" />
                            <label class="form-label" for="form3Example1m">Ville</label>
                            </div>
                        </div>
                        <div class="col-md-6 mb-4">
                            <div class="form-outline">
                            <input type="text" id="form3Example1n" class="form-control form-control-lg" />
                            <label class="form-label" for="form3Example1n">Code postal</label>
                            </div>
                        </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-4 d-flex align-items-center">
                              <div class="form-outline datepicker w-100">
                                <input type="date" class="form-control form-control-lg" id="birthdayDate" />
                                <label for="birthdayDate" class="form-label">Date de naissance</label>
                              </div>
                            </div>
                            <div class="col-md-6 mb-4">
                              <h6 class="mb-2 pb-1">Sexe: </h6>
                              <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="inlineRadioOptions" id="femaleGender"
                                  value="option1" checked />
                                <label class="form-check-label" for="femaleGender">Femme</label>
                              </div>
                              <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="inlineRadioOptions" id="maleGender"
                                  value="option2" />
                                <label class="form-check-label" for="maleGender">Homme</label>
                              </div>
                            </div>
                          </div>

                        <div class="form-outline mb-4">
                        <input type="email" id="form3Example97" class="form-control form-control-lg" />
                        <label class="form-label" for="form3Example97">Adresse mail</label>
                        </div>

                        <div class="row">
                        <div class="col-md-6 mb-4">
                            <div class="form-outline">
                            <input type="text" name="pseudo" id="form3Example1m" class="form-control form-control-lg" />
                            <label class="form-label" for="form3Example1m">Nom d'utilisateur</label>
                            </div>
                        </div>
                        <div class="col-md-6 mb-4">
                            <div class="form-outline">
                            <input type="tel" id="form3Example1n" class="form-control form-control-lg" />
                            <label class="form-label" for="form3Example1n">Téléphone</label>
                            </div>
                        </div>
                        </div>


                         <div class="row">
                        <div class="col-md-6 mb-4">
                            <div class="form-outline">
                            <input type="password" name="password" id="mdpText" class="form-control form-control-lg" />
                            <label class="form-label" for="mdpText">Mot de passe</label>
                            </div>
                        </div>
                        <div class="col-md-6 mb-4">
                            <div class="form-outline">
                            <input type="password" id="confMDPText" class="form-control form-control-lg" />
                            <label class="form-label" for="confMDPText">Confirmer mot de passe</label>
                            </div>
                        </div>
                        </div>

                        <div class="d-flex justify-content-end pt-3">
                        <button type="button" class="btn btn-light btn-lg">Tout supprimer</button>
                        <button type="submit" class="btn btn-warning btn-lg ms-2">Confirmer</button>
                        </div>

                    </div>
                    </div>
                </div>
                </div>
            </div>
            </div>
        </div>
        </form>
        </section>
        END;
        return $temp;
    }
}