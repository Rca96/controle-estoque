<?php $this->load->view('header'); ?>

    <?php $this->load->view('menu'); ?>

    <section class="content">
        <div class="container-fluid">

            <div class="row clearfix">

                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                	<?php echo $this->session->flashdata('result'); ?>

                    <div class="card">
                	<?php if(isset($alterar)){ ?>

                        <div class="header">
                            <h2>
                                EDITAR - <span style="font-size:12px">TIPO PRODUTO (<b><?=$alterar['nome']?></b>)</span>
                            </h2>
                        </div>
                        <div class="body">
                            <form id="form1" class="form-horizontal" action="<?=base_url()?>tipoProd/alterar" method="POST">
                            	<div class="form-group">
					              <label class="col-sm-1 control-label" for="nome">Nome:</label>
					              <div class="col-sm-6">
					              	<div class="form-line">
					                	<input type="text" class="form-control" value="<?=$alterar['nome']?>" name="nome" id="nome" placeholder="Digite o tipo de produto">
					                </div>
					              </div>
					              <div class="col-sm-4">
					              <input type="hidden" name="id_tipoproduto" value="<?=$alterar['id']?>">
					              	<button type="submit" id="editar" class="btn btn-warning m-t-15 waves-effect">ALTERAR</button> &nbsp;
					              	<a href="../" class="btn btn-default m-t-15 waves-effect">VOLTAR</a>
					              </div>
					            </div>     
                            </form>
                        </div>

                    <?php }else{ ?>

                    	<div class="header">
                            <h2>
                                CADASTRAR - <span style="font-size:12px">TIPO PRODUTO</span>
                            </h2>
                        </div>
                        <div class="body">
                            <form id="form1" class="form-horizontal" action="<?=base_url()?>tipoProd/cadastrar" method="POST">
                            	<div class="form-group">
					              <label class="col-sm-1 control-label" for="nome">Nome:</label>
					              <div class="col-sm-6">
					              	<div class="form-line">
					                	<input type="text" class="form-control" name="nome" id="nome" placeholder="Digite o tipo de produto">
					                </div>
					              </div>
					              <div class="col-sm-4">
					              	<button type="submit" id="cadastrar" class="btn btn-success m-t-15 waves-effect">CADASTRAR</button>
					              </div>
					            </div>      
                            </form>
                        </div>

                    <?php } ?>
                    </div>
                </div>

                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                    <div class="card">
                        <div class="header">
                            <h2>
                                CONSULTAR - <span style="font-size:12px">TIPO PRODUTO</span>
                            </h2>
                        </div>
                        <div class="body">
                           <table class="table table-bordered table-striped table-hover js-basic-example dataTable" id="data-table-no-extension" >
		                        <thead>
		                            <tr>
				                      	<th><b>Nome</b></th>
				                      	<th style="max-width:100px"><b>Editar / Excluir</b></th>
		                            </tr>
		                        </thead>
		                        <tbody>
		                        <?php
		                        	foreach ($tipos as $row) { ?>

                        		 		<tr>
                                      		<td><?=$row->nome?></td>
                                          	<td>
                                      			<a href="<?=base_url()?>tipoProd/editar/<?=$row->id?>" class="btn btn-primary waves-effect">
                                    				<i class="material-icons">edit</i>
                                				</a>
                                				<button type="button" 
                                				class="btn btn-danger waves-effect" 
                                				data-trigger="focus" 
                                				data-container="body" 
                                				data-toggle="popover" 
                                				data-placement="top" 
                                				data-original-title="Confirmar exclusão?"
                                				data-content="
                                						<form action='<?=base_url()?>tipoProd/deletar' method='POST'>
                                							<button type='submit' name='id_tipoproduto' value='<?=$row->id?>' class='btn btn-success'>
                                								<i class='material-icons'>done</i> Sim
                                							</button>
                                							<span class='btn btn-danger'>
                                								<i class='material-icons'>close</i> Não
                                							</span>
                                						</form>">
			                                        <i class="material-icons">delete</i>
			                                    </button>
                                			</td>
                                        </tr>

		                    <?php	} ?>
		                        </tbody>
		                    </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
    
<?php $this->load->view('footer'); ?>

<script>

	$("#form1").validate({
      rules:{
        servico: "required",
      },
      messages: {
        servico: "Campo obrigatório!",
      }
    });

</script>