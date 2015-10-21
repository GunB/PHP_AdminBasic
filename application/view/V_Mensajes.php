<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function error_mensaje($mensaje, $url_back = BASE_URL) {
    ?>
    <div class="container">
        <div class="row">
            <div class="alert alert-danger alert-dismissible" role="alert">
                <a type="button" href="<?php echo $url_back ?>" class="close" data-dismiss="alert"><span aria-hidden="true">X</span>
                    <span class="sr-only">Close</span>
                </a>
                <strong><i class="fa fa-warning"></i> Danger!</strong> <marquee><p style="font-family: Impact; font-size: 18pt"><?php echo $mensaje ?></p></marquee>
            </div>
        </div>
    </div>
    <?php
}

function correct_mensaje($mensaje, $url_back = BASE_URL) {
    ?>
    <div class="container">
        <div class="row">
            <div class="alert alert-success alert-dismissible" role="alert">
                <a type="button" href="<?php echo $url_back ?>" class="close" data-dismiss="alert"><span aria-hidden="true">X</span>
                    <span class="sr-only">Close</span>
                </a>
                <strong><i class="fa fa-warning"></i> Danger!</strong> <marquee><p style="font-family: Impact; font-size: 18pt"><?php echo $mensaje ?></p></marquee>
            </div>
        </div>
    </div>
    <?php
}
