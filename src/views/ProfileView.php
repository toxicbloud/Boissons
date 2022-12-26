<?php 

namespace boissons\views;

use boissons\models\User;
class ProfileView
{
    private $user;
    public function __construct($rq,User $user=null)
    {
        $this->rq = $rq;
        $this->user = $user;
        // $user->username = $body['pseudo'];
        // $user->lastname = $body['lastname'];
        // $user->sex = $body['gender'];
        // $user->email = $body['email'];
        // $user->dateofbirth = $body['dateofbirth'];
        // $user->address = $body['address'];
        // $user->ZIPcode = $body['zipcode'];
        // $user->city = $body['city'];
        // $user->phone = $body['phone'];
        // $user->password = $hash;
    }
    public function render()
    {
        $content = $this->html();
        $vue = new View($content, 'Edition Profil', $this->rq);
        $vue->addJSScript('edit.js');
        return $vue->getHtml();
    }
    public function html()
    {
        $path = $this->rq->getUri()->getBasePath();
        $femme = $this->user->sex == 'F' ? 'checked' : '';
        $homme = $this->user->sex == 'H' ? 'checked' : '';
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
        <form id="form" action="register" method="post">
        <div class="container py-5 h-100">
            <div class="row-md d-flex justify-content-center align-items-center h-100">
            <div class="col">
                <div class="card card-registration my-2" style="border: rgba( 0, 0, 0, .0);">
                <div class="row g-0">
                    <div class="col-xl-6 d-none d-xl-block">
                    <img src="$path/Photos/imageRegister.png" alt="Sample photo" class="img-fluid"/>
                    </div>
                    <div class="col-xl-6" style="background-color: rgba(140, 140, 140, .2);">
                    <div class="card-body p-md-5 text-black">
                        <h3 class="mb-5 text-uppercase">Profil</h3>

                        <div class="row">
                        <div class="col-md-6 mb-2">
                            <div class="form-outline">
                            <input name="firstname" type="text" id="firstnameText" class="form-control form-control-lg" value="{$this->user->firstname}" />
                            <label class="form-label" for="firstnameText">Prénom</label>
                            </div>
                        </div>
                        <div class="col-md-6 mb-2">
                            <div class="form-outline">
                            <input name="lastname" type="text" id="lastnameText" class="form-control form-control-lg" value="{$this->user->lastname}" />
                            <label class="form-label" for="lastnameText">Nom</label>
                            </div>
                        </div>
                        </div>

                        <div class="form-outline mb-2">
                        <input name="address" type="text" id="streetText" class="form-control form-control-lg" value="{$this->user->address}" />
                        <label class="form-label" for="streetText">Adresse</label>
                        </div>

                        <div class="row">
                        <div class="col-md-6 mb-2">
                            <div class="form-outline">
                            <input name="city" type="text" id="cityText" class="form-control form-control-lg" value="{$this->user->city}" />
                            <label class="form-label" for="cityText">Ville</label>
                            </div>
                        </div>
                        <div class="col-md-6 mb-2">
                            <div class="form-outline">
                            <input name="zipcode" type="text" id="zipText" class="form-control form-control-lg" value="{$this->user->ZIPcode}"/>
                            <label class="form-label" for="zipText">Code postal</label>
                            </div>
                        </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-2 d-flex align-items-center">
                              <div class="form-outline datepicker w-100">
                                <input name="dateofbirth" type="date" class="form-control form-control-lg" id="birthdayDateText" value="{$this->user->dateofbirth}" />
                                <label for="birthdayDateText" class="form-label">Date de naissance</label>
                              </div>
                            </div>
                            <div class="col-md-6 mb-2">
                              <h6 class="mb-2 pb-1">Sexe: </h6>
                              <div class="form-check form-check-inline" >
                                <input class="form-check-input" type="radio" name="gender" id="femaleGender"
                                  value="F" $femme />
                                <label class="form-check-label" for="femaleGender">Femme</label>
                              </div>
                              <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="gender" id="maleGender"
                                  value="H" $homme />
                                <label class="form-check-label" for="maleGender">Homme</label>
                              </div>
                            </div>
                          </div>

                        <div class="form-outline mb-2">
                        <input name="email" type="email" id="emailText" class="form-control form-control-lg" value="{$this->user->email}" />
                        <label class="form-label" for="emailText">Adresse mail</label>
                        </div>

                        <div class="row">
                        <div class="col-md-6 mb-2">
                            <div class="form-outline">
                            <input type="text" name="pseudo" id="pseudoText" class="form-control form-control-lg" value="{$this->user->username}" />
                            <label class="form-label" for="pseudoText">Nom d'utilisateur</label>
                            </div>
                        </div>
                        <div class="col-md-6 mb-2">
                            <div class="form-outline">
                            <input name="phone" type="tel" id="phoneText" class="form-control form-control-lg"  value="{$this->user->phone}"/>
                            <label class="form-label" for="phoneText">Téléphone</label>
                            </div>
                        </div>
                        </div>


                         <div class="row">
                        <div class="col-md-6 mb-5">
                            <div class="form-outline">
                            <input type="password" name="password" id="mdpText" class="form-control form-control-lg" autocomplete="new-password" />
                            <label class="form-label" for="mdpText">Mot de passe</label>
                            </div>
                        </div>
                        <div class="col-md-6 mb-5">
                            <div class="form-outline">
                            <input type="password" id="confMDPText" class="form-control form-control-lg" />
                            <label class="form-label" for="confMDPText">Confirmer mot de passe</label>
                        </div>
                            </div>
                        </div>
                        <div id="info" class="text-danger d-none pl-5">
                        
                        </div>
                        <div class="d-flex justify-content-end pt-3">
                        <button type="submit" class="btn btn-warning btn-lg">Confirmer</button>
                        </div>
                        </div>
                        
                        
                    </div>
                    </div>
                </div>
                </div>
            </div>
            </div>
        </div>
        </form>
        <div id="toastContainer" class="toast-container position-absolute top-0 end-0 p-3">
        </div>
        </section>
END;
        return $temp;
    }
}