<?php $this->load->view('header'); ?>
    <?php $this->load->view('menu'); ?>

    <link rel="stylesheet" href="<?=base_url()?>plugins/lightbox/css/lightbox.min.css"/>

    
    <section class="content">
        <div class="container-fluid">
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                    <?php echo $this->session->flashdata('result'); ?>

                <form id="form1" class="form-horizontal"  action="<?=base_url()?>relatorios/pdfProdutos" method="POST" target ="_blank">
                    <div class="card">
                        <div class="header">
                            <h2>
                                CONSULTA DAS PRODUTOS
                            </h2>
                            <ul class="header-dropdown m-r--5">
									<!-- <li>
										<a class="btn bg-grey waves-effect" data-toggle="modal" href="#" style="cursor:pointer;">
											<i class="material-icons">print</i>
										</a> 
									</li> -->
									<li>
									 <a href="<?=base_url()?>Produto/cadastrar" class="btn btn-success waves-effect ">
											NOVO CADASTRO
										</a>
									</li>
								</ul> 
                        </div>
                      <div class="body">
                         <div>
                             <table class="table table-bordered table-striped table-hover js-basic-example dataTable" id="datatable-produto" style="width: 100%" >
                                    <thead>
                                        <tr style="font-size:12px">
                                            <th><b>ID</b></th> 
                                            <th><b>Tipo Produto</b></th>   
                                            <th><b>Nome do Produto</b></th> 
                                            <th><b>Codigo Produto</b></th>   
                                            <th><b>Unid</b></th>      
                                            <th><b>Qtda Minima</b></th>                                            
                                            <th><b>Qtda Estoque</b></th>
                                            <!-- <th><b>Observação</b></th> -->
                                            
                                        </tr>
                                    </thead>
                              <tbody>
                                   <?php
                                            
                                      foreach ($produtos as $row) { 
                                          ?>
                                           <tr style="font-size:12px">
                                             <td><?=$row->id?></td>   
                                             <td style='width:7%;'><?=$row->nome_tipo?></td>   
                                             <td><?=$row->nome_produto?></td>   
                                             <td  style='width:7%;'><?=$row->cod_produto?></td>   
                                             <td><?=$row->unidade?></td>
                                             <td><?=$row->qtd_minima?></td>   
                                             <!-- <td><?=$row->observacao?></td>    -->
                                             <td>
                                      			<a href="<?=base_url()?>produto/editar/<?=$row->id?>" class="btn btn-primary waves-effect">
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
                                						<form action='<?=base_url()?>produto/deletar' method='POST'>
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
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>                        
                    </div>
                </form>
                </div>
            </div>
        </div>
 
      </div>

    </section>


<?php $this->load->view('footer'); ?>

<script>

    $(document).ready(function(){


        $("#datatable-produto").DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Portuguese-Brasil.json"
            },
            "order": [[0, "desc"]],
            "responsive": true,
        });
    });

 

</script>

<script src="<?=base_url()?>plugins/lightbox/js/lightbox.min.js"></script>