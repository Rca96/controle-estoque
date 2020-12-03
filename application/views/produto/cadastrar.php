<?php $this->load->view('header'); ?>

<?php $this->load->view('menu'); ?>

<style>
    .bootstrap-filestyle .form-control {
        border: 1px solid #ccc !important;
        padding: 6px 12px;
        font-size: 14px;
        line-height: 1.429;
        color: #555;
        background-color: #fff;
        background-image: none;
        border-radius: 4px;
        background-color: #eee;
        height: 30px;
        z-index: 3;
    }
</style>
<section class="content">
    <div class="container-fluid">

        <div class="row clearfix">

            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                <?php echo $this->session->flashdata('result'); ?>

                <div class="card">

                    <div class="header">
                        <h2>
                            CADASTRAR - <span style="font-size:12px">PRODUTO</span>
                        </h2>
                    </div>
                    <div class="body">
                        <form id="form1" class="form-horizontal" action="<?= base_url() ?>produto/cadastrar" method="POST" enctype="multipart/form-data">
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="tipo">Código Produto:</label>
                                <div class="col-sm-4">
                                    <div class="form-line">
                                        <input type="text" class="form-control" name="cod_produto" id="cod_produto" placeholder="Digite o codigo produto">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="tipo">Nome:</label>
                                <div class="col-sm-4">
                                    <div class="form-line">
                                        <input type="text" class="form-control" name="nome" id="nome" placeholder="Digite o nome produto">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">

                                <label class="col-sm-2 control-label" for="unidades">Unidade Medida:</label>
                                <div class="col-sm-4">
                                    <select class="form-control show-tick" data-live-search="true" name="unidades" id="unidades">
                                        <option value="">Selecione...</option>
                                        <?php
                                        foreach ($unidades as $value)
                                            echo "<option value='$value->id'>$value->nome</option>";
                                        ?>
                                    </select>
                                </div>

                                <label class="col-sm-2 control-label" for="nome">Tipo de Produto:</label>
                                <div class="col-sm-4">
                                    <select class="form-control show-tick" data-live-search="true" name="tipoprodutos[]" id="tipoprodutos" multiple="multiple">

                                        <?php
                                        foreach ($tipoprodutos as $value)
                                            echo "<option value='$value->id'>$value->nome</option>";

                                        ?>
                                    </select>
                                </div>

                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="qtd_minima">Quantidade Mínima:</label>
                                <div class="col-sm-4">
                                    <div class="form-line">
                                        <input type="text" class="form-control" name="qtd_minima" id="qtd_minima" placeholder="Digite quantidade minima do estoque">
                                    </div>
                                </div>

                                <label class="col-sm-2 control-label" for="qtd_maxima">Quantidade Maxima:</label>
                                <div class="col-sm-4">
                                    <div class="form-line">
                                        <input type="text" class="form-control" name="qtd_maxima" id="qtd_maxima" placeholder="Digite quantidade maxima do estoque">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="nome" class="control-label col-sm-2">Observação:</label>
                                <div class="col-sm-8">
                                    <div class="form-line">
                                        <textarea class="form-control" maxlength="200" name="observacao" rows="2" placeholder="Observações não é obrigatorio"></textarea>
                                    </div>
                                </div>

                            </div>
                            <input name="userfile[]" id="userfile" type="file" multiple="multiple" />

                            <div class="form-group">
                                <div class="col-sm-4">
                                    <button type="submit" id="cadastrar" name="cadastrar" class="btn btn-success m-t-15 waves-effect">CADASTRAR</button>
                                </div>
                            </div>

                        </form>
                    </div>

                </div>
            </div>

            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                <div class="card">
                    <div class="header">
                        <h2>
                            CONSULTAR - <span style="font-size:12px">PRODUTO</span>
                        </h2>
                    </div>
                    <div class="body">
                        <table class="table table-bordered table-striped table-hover js-basic-example dataTable" id="datatable-produto" style="width: 100%">
                            <thead>
                                <tr style="font-size:12px">
                                    <th><b>ID</b></th>
                                    <th><b>Código</b></th>
                                    <th><b>Nome</b></th>
                                    <th><b>Tipo Produto</b></th>
                                    <th><b>Unid</b></th>
                                    <th><b>Mínima</b></th>
                                    <th><b>Maxima</b></th>
                                    <th><b>Estoque</b></th>
                                    <th><b>Fotos</b></th>
                                    <th><b>Gerenciar</b></th>

                                </tr>
                            </thead>
                            <tbody>
                                <?php

                                foreach ($produtos as $row) {
                                    $qtd_minima = str_replace('.', ',', $row->qtd_minima);
                                    $qtd_maxima = str_replace('.', ',', $row->qtd_maxima);
                                    $qtd_estoque = str_replace('.', ',', $row->qtd_estoque);

                                    $foto = '';
                                    $fotos = $row->file_name;
                                    foreach ($fotos as $f) {
                                        $foto_name = str_replace($row->nome_produto . '_', '', $f);
                                        $foto .=  "<a target='_blank' href='" . base_url() . URL_UPLOAD_LOCAL . $f . "'>$foto_name</a><br>";
                                    }

                                ?>
                                    <tr style="font-size:12px">
                                        <td style='width:4%;'><?= $row->id ?></td>
                                        <td style='width:7%;'><?= $row->cod_produto ?></td>
                                        <td><?= $row->nome_produto ?></td>
                                        <td style='width:15%;'><?= $row->nome_tipo ?></td>
                                        <td style='width:5%;'><?= $row->unidade ?></td>
                                        <td><?= $qtd_minima ?></td>
                                        <td><?= $qtd_maxima ?></td>
                                        <td><?= $qtd_estoque ?></td>
                                        <td><?= $foto ?></td>


                                        <div>
                                            <td>
                                                <a href="<?= base_url() ?>Produto/editar/<?= $row->id ?>" class="btn btn-primary waves-effect">
                                                    <i class="material-icons">edit</i>
                                                </a>

                                                <button type="button" class="btn btn-danger waves-effect" data-trigger="focus" data-container="body" data-toggle="popover" data-placement="top" data-original-title="Confirmar exclusão?" data-content="
															<form action='<?= base_url() ?>produto/deletar' method='POST'>
																<input type='hidden' id='id_produto' name='id_produto' value='<?= $row->id  ?>'/>
																<button type='submit' name='id_anexo' value='' class='btn btn-success'>
																	<i class='material-icons'>done</i> Sim
																</button>
																<span class='btn btn-danger'>
																	<i class='material-icons'>close</i> Não
																</span>
															</form>">
                                                    <i class="material-icons">delete</i>
                                                </button>
                                            </td>
                                        </div>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php $this->load->view('footer'); ?>

<script type="text/javascript" src="<?= base_url() ?>js/custom/bootstrap-filestyle.min.js"></script>

<script>
    //inserimos as mascaras nos campos 

    $("#form1").validate({
        rules: {
            nome: "required",


        },
        messages: {
            nome: "Campo obrigatório!",


        }
    });


    $("#cadastrar").click(function() {
        var $fileUpload = $("#upload");
        if (parseInt($fileUpload.get(0).files.length) > 3) {
            alert("Você só pode subir 3 arquivos!");
        }
    });



    $('#qtaMinima').mask("###000,000", {
        reverse: true
    });
    $('#qtaMaxima').mask("###000,000", {
        reverse: true
    });
    //    $('#valor_unit').mask("###0,00", {reverse: true});

    var table = $("#data-table-custom-2").DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Portuguese-Brasil.json"
        },
        "order": [
            [0, "asc"]
        ]
    });

    $('#nome_prod').keypress(function(e) {
        var regex = new RegExp("^[- A-Z-a-záàâãéèêíïóôõöúçñÁÀÂÃÉÈÊÍÏÓÒÖÚÇÑ]+$");
        var str = String.fromCharCode(!e.charCode ? e.which : e.charCode);
        if (regex.test(str)) {
            return true;
        }
        e.preventDefault();
        return false;
    });

    $('#cod_produto').keypress(function(e) {
        var regex = new RegExp("^[ A-Z-a-z-1-9]+$");
        var str = String.fromCharCode(!e.charCode ? e.which : e.charCode);
        if (regex.test(str)) {
            return true;
        }
        e.preventDefault();
        return false;
    });

    $('#observacao').keypress(function(e) {
        var regex = new RegExp("^[- A-Z-a-záàâãéèêíïóôõöúçñÁÀÂÃÉÈÊÍÏÓÒÖÚÇÑ]+$");
        var str = String.fromCharCode(!e.charCode ? e.which : e.charCode);
        if (regex.test(str)) {
            return true;
        }
        e.preventDefault();
        return false;
    });
</script>

<script>
    $(document).ready(function() {

        $("#datatable-produto").DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Portuguese-Brasil.json"
            },
            "order": [
                [0, "desc"]
            ],
            "responsive": true,
        });
    });
</script>