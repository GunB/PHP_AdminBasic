<?php

function showMenu() {
    ?>

    <nav class="navbar navbar-default navbar-inverse" role="navigation">
        <div class="container-fluid">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="<?php echo site_url() ?>">Drogueria COLO</a>
            </div></div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
                <li class="active"><a href="#">Consultar Productos</a></li>
                <li><a href="#">Ofertas</a></li>
                <li><a href="#">Contactenos</a></li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">menu<span class="caret"></span></a>
                    <ul class="dropdown-menu" role="menu">
                        <li><a href="#">Action</a></li>
                        <li><a href="#">Another action</a></li>
                        <li><a href="#">Something else here</a></li>
                        <li class="divider"></li>
                        <li><a href="#">Separated link</a></li>
                        <li class="divider"></li>
                        <li><a href="#">One more separated link</a></li>
                    </ul>
                </li>
            </ul>
            <!--<form class="navbar-form navbar-left" role="search">
                <div class="form-group">
                    <input type="text" class="form-control" placeholder="Nombre del Producto">
                </div>
                <button type="submit" class="btn btn-default">Buscar</button>


            </form>-->

            <ul class="nav navbar-nav navbar-right">

                <li><p class="navbar-text">Ya tienes tu Cuenta?</p></li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><b>iniciar sesion</b> <span class="caret"></span></a>
                    <ul id="login-dp" class="dropdown-menu">
                        <li>
                            <div class="row">
                                <div class="col-md-12">
                                    <!--Login via
                                    <div class="social-buttons">
                                        <a href="#" class="btn btn-fb"><i class="fa fa-facebook"></i> Facebook</a>
                                        <a href="#" class="btn btn-tw"><i class="fa fa-twitter"></i> Twitter</a>
                                    </div>
                                    or-->
                                    Iniciar Sesion
                                    <form class="form" role="form" method="post" action="<?php echo site_url("C_Usuario/iniciar_sesion"); ?>" accept-charset="UTF-8" id="login-nav">
                                        <div class="form-group">
                                            <label class="sr-only" for="exampleInputEmail2">Email address</label>
                                            <input name="correo" type="email" class="form-control" id="exampleInputEmail2" placeholder="Correo Electronico " required>
                                        </div>
                                        <div class="form-group">
                                            <label class="sr-only" for="exampleInputPassword2">Password</label>
                                            <input type="password" name="clave" class="form-control" id="exampleInputPassword2" placeholder="Contraseña" required>
                                            <!--<div class="help-block text-right"><a href="">Olvido la Contraseña?</a></div>-->
                                        </div>
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-primary btn-block">Entrar</button>
                                        </div>
                                        <!--<div class="checkbox">
                                            <label>
                                                <input type="checkbox"> Recordar Contraseña
                                            </label>
                                        </div>-->
                                    </form>
                                </div>
                                <div class="bottom text-center">
                                    Nueva Cuenta ? <a href="<?php echo site_url("inicio/registrarse") ?>"><b>Crear</b></a>
                                </div>
                            </div>
                        </li>
                    </ul>
                </li>
            </ul>
        </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
    </nav>




    <?php
}
