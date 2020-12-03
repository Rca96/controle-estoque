<?php $this->load->view('header'); ?>

    <?php $this->load->view('menu'); ?>
    
    <section class="content">
        <div class="container-fluid">
            
            <?php echo $this->session->flashdata('result'); ?>

            <div class="row clearfix">
                <form id="form_notificacoes" action="<?=base_url()?>ocorrencia/" class="form-horizontal" method="POST">
                    <input type="hidden" name="status_selected" id="status_selected" value="">
                </form>

                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                    <div class="info-box-3 bg-deep-orange hover-zoom-effect filtro" data="1" style="cursor:pointer">
                        <div class="icon">
                            <i class="material-icons">autorenew</i>
                        </div>
                        <div class="content">
                            <div class="text">PRODUTOS</div>
                            <div class="number count-to" data-from="0" data-to="<?=@$total_abertas?>" data-speed="1000" data-fresh-interval="20"></div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">     
                          <div class="row clearfix"
                        <div class="header">
                            <h2>
                                 PRODUTOS COM ESTOQUE MINIMOS
                            </h2>
                        </div>
                        <div class="body">
                            <table class="table table-bordered table-striped table-hover js-basic-example" id="data-table-custom-2">
                                <thead>
                                    <tr>
                              
                                        <th><b>Nome Produto</b></th>
                                        <th><b>Codigo Produto</b></th>
                                        <th><b>Quantidade </b></th>
                                        <th><b>Ver produto</b></th>
                                    </tr>
                                </thead>

                                <tfoot>
                                    <tr>
                                        <th><b>Nome Produto</b></th>
                                        <th><b>Codigo Produto</b></th>
                                        <th><b>Quantidade</b></th>
                                        <th><b>Ver produto</b></th>
                                    </tr>
                                </tfoot>
                                <tbody>
                  
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
        </div>
    </section>


<?php $this->load->view('footer'); ?>

<script>
    var table = $("#data-table-custom-2").DataTable({
        "language": {
          "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Portuguese-Brasil.json"
        },
        "order": [[0, "asc"]]
    });
</script>

