<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function showHeaders() {
    ?>
    <html>
        <head>
            <meta charset="utf-8" /> 
            <?php
            $m_data = new application\model\M_Data();
            //$array = $m_data->listFolderFiles("assets");
            $array = $m_data->directoryToArray("assets", true);

            foreach ($array as $key => $value) {
                if (endsWith($value, ".js")) {
                    ?>
                    <script src="<?php echo base_url($value) ?>"></script>
                    <?php
                } elseif (endsWith($value, ".css")) {
                    ?>
                    <link href="<?php echo base_url($value)  ?>" rel="stylesheet">
                    <?php
                }
            }
            ?>
        </head>
        <body>
            <?php
        }

        function showFoot() {
            ?>

        </body>
    </html>
    <?php
}
