<?php
$classes = array();
$apelidos = array();
$this->data['centro_custo'] = $this->id_centro_custo = $this->session->userdata['logged_in']['id_centro_custo'];

if (@$menu) {
    foreach ($menu as $value) {
        $classes[] = $value->classe;
        $apelidos[] = $value->apelido;
    }
}
?>
<!-- #Top Bar -->
<section>
    <!-- Left Sidebar -->
    <aside id="leftsidebar" class="sidebar">
        <!-- User Info -->
        <div class="user-info">
            <div class="image">
                <?php
                echo '<img src="' . base_url() . 'images/user.png" width="48" height="48" alt="User" />';
                ?>
            </div>

            <div class="info-container">
                <div class="name" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?= $this->session->userdata['logged_in']['nome'] ?></div>
                <div class="name" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?= $this->session->userdata['logged_in']['nome_custo'] ?></div>

                <a href="<?= base_url() ?>login/logout" class="btn-group user-helper-dropdown">
                    <i class="material-icons">input</i>
                </a>
            </div>
        </div>

        <!-- #User Info -->
        <!-- Menu -->
        <div class="menu">
            <input type="hidden" id="tipo_estoque" name="tipo_estoque" value="<?= $tipo_estoque ?>" />
            <ul id="lista" name="lista" class="list">
                <li class="header">MENU NAVEGAÇÃO</li>

                <li <?php if ($this->uri->uri_string() == "home") {
                        echo 'class="active"';
                    } ?>>
                    <a href="<?= base_url() ?>Estoque">
                        <i class="material-icons">home</i>
                        <span>Home</span>
                    </a>
                </li>

                <li <?php if ($this->uri->uri_string() == "home") {
                        echo 'class="active"';
                    } ?>>
                    <a href="<?= base_url() ?>TipoProd">
                        <i class="material-icons">add_circle</i>
                        <span>Tipo de Produtos</span>
                    </a>
                </li>
                <li id="tipo_mov" name="tipo_mov" <?php if ($this->uri->uri_string() == "home") {
                                                        echo 'class="active"';
                                                    } ?>>
                    <a href="<?= base_url() ?>tipoMotivo">
                        <i class="material-icons">add_circle_outline</i>
                        <span>Tipo de Motivos</span>
                    </a>
                </li>
                <li <?php if ($this->uri->uri_string() == "home") {
                        echo 'class="active"';
                    } ?>>
                    <a href="<?= base_url() ?>produto">
                        <i class="material-icons">add_shopping_cart</i>
                        <span>Produtos</span>
                    </a>
                </li>


                <li <?php if ($this->uri->uri_string() == "home") {
                        echo 'class="active"';
                    } ?>>
                    <a href="<?= base_url() ?>EntradaSaida">
                        <i class="material-icons">swap_horiz</i>
                        <span>Estoque / movimento</span>
                    </a>
                </li>

                <!-- <li>
                    <a href="javascript:void(0)" class="menu-toggle">
                        <i class="material-icons">picture_as_pdf</i>
                        <span>Relatórios</span>
                    </a>
                    <ul class="ml-menu">
                        <li>
                            <a href="#"><span>Entradas</span></a>
                        </li>
                        <li>
                            <a href="#"><span>Saídas</span></a>
                        </li>
                        <li>
                            <a href="#"><span>Solicitantes</span></a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="javascript:void(0)" class="menu-toggle">
                        <i class="material-icons">pie_chart</i>
                        <span>Grafico</span>
                    </a>
                    <ul class="ml-menu">
                        <li>
                            <a href="#"><span>Por Entradas</span></a>
                        </li>
                        <li>
                            <a href="#"><span>Por Saidas</span></a>
                        </li>
                    </ul>
                </li> -->
            </ul>
        </div>

        <!-- #Menu -->
        <!-- Footer -->
        <div class="legal">
            <div class="copyright">
                &copy; <?= date('Y') ?> <a href="javascript:void(0);"><?= $this->session->userdata['logged_in']['id_centro_custo'] ?></a>.
            </div>
            <div class="version">
                <b>Versão: </b> 1.1
            </div>
        </div>
        <!-- #Footer -->
    </aside>
    <!-- #END# Left Sidebar -->

</section>

<script>
    window.onload = function() {
        var tipoestoque = $('#tipo_estoque').val();
        if (tipoestoque != 1) {
            //$("#tipo_mov").hide();
            document.getElementById("lista").children[3].style.display = "none"
            document.getElementById("tipo_mov").style.display = "none";
            // document.getElementById("tipo_mov").style.display = "none";
        }

    };
</script>