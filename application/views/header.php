<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>Painel | CONTROLE DE ESTOQUE - PMA</title>
    <!-- Favicon-->
    <link rel="icon" href="<?= base_url() ?>favicon.gif" type="image/x-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&subset=latin,cyrillic-ext" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css">

    <!-- Bootstrap Core Css -->
    <link href="<?= base_url() ?>plugins/bootstrap/css/bootstrap.css" rel="stylesheet">

    <!-- Waves Effect Css -->
    <link href="<?= base_url() ?>plugins/node-waves/waves.css" rel="stylesheet" />

    <!-- Animation Css -->
    <link href="<?= base_url() ?>plugins/animate-css/animate.css" rel="stylesheet" />

    <!-- Bootstrap Spinner Css -->
    <link href="<?= base_url() ?>plugins/jquery-spinner/css/bootstrap-spinner.css" rel="stylesheet">

    <!-- Morris Chart Css-->
    <link href="<?= base_url() ?>plugins/morrisjs/morris.css" rel="stylesheet" />

    <!-- JQuery DataTable Css -->
    <link href="<?= base_url() ?>plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="<?= base_url() ?>plugins/table/responsive.dataTables.min.css">

    <!-- Bootstrap Select Css -->
    <link href="<?= base_url() ?>plugins/bootstrap-select/css/bootstrap-select.css" rel="stylesheet" />

    <!-- Multi Select Css -->
    <link href="<?= base_url() ?>plugins/multi-select/css/multi-select.css" rel="stylesheet">

    <!-- <link rel="stylesheet" href="js/custom/datatables.min.css"> -->
    <link href="<?= base_url() ?>css/custom/jquery-confirm.css" rel="stylesheet" type="text/css" />
    <link href="<?= base_url() ?>css/custom/buttons.dataTables.min.css" rel="stylesheet" type="text/css" />

    <!-- Custom Css -->
    <link href="<?= base_url() ?>css/style.css" rel="stylesheet">

    <!-- AdminBSB Themes. You can choose a theme from css/themes instead of get all themes -->
    <link href="<?= base_url() ?>css/themes/all-themes.css" rel="stylesheet" />

    <!-- datetimepicker -->
    <link href="<?= base_url() ?>js/custom/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css" rel="stylesheet" />

    <style>
        .ui-autocomplete {
            position: absolute;
            z-index: 1000;
            cursor: default;
            padding: 0;
            margin-top: 2px;
            list-style: none;
            background-color: #ffffff;
            border: 1px solid #ccc -webkit-border-radius: 5px;
            -moz-border-radius: 5px;
            border-radius: 5px;
            -webkit-box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);
            -moz-box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);
            box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);
        }

        .ui-autocomplete>li {
            padding: 3px 20px;
        }

        .dropdown-menu.open {
            overflow: inherit !important;
        }

        .ui-autocomplete>li.ui-state-focus {
            background-color: #DDD;
        }

        .ui-helper-hidden-accessible {
            display: none;
        }
    </style>

</head>

<body class="theme-blue-grey">
    <div class="page-loader-wrapper">
        <div class="loader">
            <div class="preloader">
                <div class="spinner-layer pl-red">
                    <div class="circle-clipper left">
                        <div class="circle"></div>
                    </div>
                    <div class="circle-clipper right">
                        <div class="circle"></div>
                    </div>
                </div>
            </div>
            <p>Por favor, aguarde...</p>
        </div>
    </div>
    <div class="overlay"></div>

    <nav class="navbar">
        <div class="container-fluid">
            <div class="navbar-header">
                <a href="Home" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse" aria-expanded="false"></a>
                <a href="javascript:void(0);" class="bars"> </a>
                <a class="navbar-brand"> CONTROLE DE ESTOQUE - PMA </a>

            </div>

            <div class="collapse navbar-collapse" id="navbar-collapse">

                <ul class="nav navbar-nav navbar-right">
                    <!-- #END# Call Search -->
                    <!-- Notifications -->
                    <?php if (@$qtd_minimos) { ?>
                        <li class="dropdown">
                            <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button">
                                <i class="material-icons">notifications</i>
                                <span class="label-count"><?= count($qtd_minimos) ?></span>

                            </a>

                            <ul class="dropdown-menu">
                                <li class="header" style="font-size:20px;">ESTOQUE NO MINIMO</li>
                                <li class="body">
                                    <ul class="menu">
                                        <?php foreach ($qtd_minimos as $row) { ?>
                                            <li>
                                                <a href="<?= base_url() ?>Produto/editar/<?= $row->idproduto ?>">

                                                    <div class="menu-info">
                                                        <div class="body">
                                                            <table class="table table-bordered table-striped table-hover js-basic-example cellspacing='0' dataTable" id="tabela">
                                                                <thead>
                                                                    <tr>
                                                                        <th><b>ID</b></th>
                                                                        <th><b>Codigo</b></th>
                                                                        <th><b>Nome</b></th>
                                                                        <th><b>Minima</b></th>
                                                                        <th><b>Estoque</b></th>
                                                                        <th style="max-width:100px"><b>Editar</b></th>
                                                                    </tr>
                                                                </thead>

                                                                <tbody>

                                                                    <?php

                                                                    foreach ($qtd_minimos as $row) {
                                                                        $quantidade = number_format($row->quantidade, 3, '.', ',');
                                                                        $qtd_minima = number_format($row->quantidade, 3, '.', ',');
                                                                        $qtd_minima = str_replace('.', ',', $row->qtd_minima);
                                                                        $quantidade = str_replace('.', ',', $row->quantidade);
                                                                    ?>
                                                                        <tr>
                                                                            <td style='width:4%;'><?= $row->id ?></td>
                                                                            <td style='width:5%;'><?= $row->cod_produto ?></td>
                                                                            <td style='width:35%;'><?= $row->nome_produto ?></td>
                                                                            <td style='width:5%;'><?= $qtd_minima ?></td>
                                                                            <td style='width:5%;'><?= $quantidade ?></td>
                                                                            <td style="min-width:5px">
                                                                                <a href="<?= base_url() ?>Produto/editar/<?= $row->idproduto ?>" class="btn btn-success waves-effect">
                                                                                    <i class="material-icons">visibility</i>
                                                                                </a>
                                                                            </td>

                                                                        </tr>

                                                                    <?php } ?>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </a>
                                            </li>

                                        <?php } ?>
                                    </ul>

                                </li>
                            </ul>


                        </li>
                    <?php } ?>
                    <li>
                        <a href="<?= base_url() ?>login/logout" class="js-search" data-close="true">
                            <i class="material-icons">input</i> <span style="position:relative;top:-6px">SAIR</span>
                        </a>
                    </li>
                </ul>
            </div>
    </nav>


    <script>
        $(document).ready(function() {

            var table = $('#tabela').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Portuguese-Brasil.json"
                },

                "columnDefs": [{
                    "visible": false,
                    "targets": [0]
                }]
            });

        });
    </script>