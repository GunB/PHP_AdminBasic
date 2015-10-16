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
            <?php
            $m_data = new application\model\M_Data();
            $array = $m_data->listFolderFiles("assets");
            foreach ($array as $key => $value) {
                if (endsWith($value, ".js")) {
                    ?>
                    <script src="<?php echo $value ?>"></script>
                    <?php
                } elseif (endsWith($value, ".css")) {
                    ?>
                    <link href="<?php echo $value ?>" rel="stylesheet">
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
