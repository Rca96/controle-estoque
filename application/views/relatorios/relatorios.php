<?php $this->load->view('header'); ?>
	<?php $this->load->view('menu'); ?>

    <section class="content">
        <div class="container-fluid">

            <?php echo $this->session->flashdata('result'); ?>

            <!-- <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="body">
                                <div class="row clearfix">
                                    <div class="col-md-3">
                                        <b>Data Inicial</b>
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <i id="data_inicial"class="material-icons">date_range</i>
                                            </span>
                                            <div class="form-line">
                                                <input type="date" class="form-control date">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row clearfix">
                                    <div class="col-md-3">
                                        <b>Data Final</b>
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <i id="data_final"class="material-icons">date_range</i>
                                            </span>
                                            <div class="form-line">
                                                <input type="date" class="form-control date">
                                            </div>
                                        </div>
                                    </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> -->
            <form id="form1" class="form-horizontal"  action="" method="POST">				
                <div class="card">
                        <div class="header">
                            <h2>
                                RELATÓRIO DE PRODUTOS
                            </h2>
                        </div>
                    <div class="body">
                            
                            <div class="col-sm-2 control-label"></div>
                            <!-- <button type="submit" id="gerarpdf" onclick="Submit('1');" value="caminho1" class="btn btn-primary waves-effect">
                                <i class="material-icons">insert_drive_file</i>
                                <span>PDF</span>
                            </button> -->
                            
                            <button type="submit" id="gerarexcel" onclick="Submit('2');" value="caminho2" class="btn btn-success waves-effect">
                                <i class="glyphicon glyphicon-save-file"></i>
                                <span>EXCEL</span>
                            </button>
                    </div>
                </div>
				</div>
            </form>
            </div>
        </div>
    </section>

<?php $this->load->view('footer'); ?>

<script>

   
    function Submit(pcaminho){

   
        if(pcaminho == 1)
        {
            document.forms[0].target = "_blank";
            document.forms[0].action = "<?=base_url()?>relatorios/pdfProdutos";
        }
        if(pcaminho == 2)
            document.forms[0].action = "<?=base_url()?>relatorios/excel";

        document.forms[0].submit();

    };
</script>