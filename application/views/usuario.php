<?php $this->load->view('header'); ?>

    <?php $this->load->view('menu'); ?>

    <section class="content">
        <div class="container-fluid">

            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                	<?php echo $this->session->flashdata('result'); ?>
                    
                	<?php if(isset($alterar)){ ?>
                    <div class="card">
                        <div class="header">
                            <h2>
                                EDITAR - <span style="font-size:12px">USUÁRIO (<b><?=$alterar['nome']?></b>)</span>
                            </h2>
                        </div>
                        <div class="body">
                            <form id="form1" class="form-horizontal" action="<?=base_url()?>usuario/alterar" method="POST">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="col-md-2 control-label" for="nome">Nome:</label>
                                        <div class="col-md-8">
                                            <label><?=$alterar['nome']?></label>
                                        </div>
                                    </div>  
                                    <div class="form-group">
                                        <label class="col-md-2 control-label" for="email">Usuário:</label>
                                        <div class="col-md-8">
                                            <label><?=$alterar['usuario']?></label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label" for="nivel">Nível:</label>
                                        <div class="col-md-8">
                                            <select class="form-control show-tick" data-live-search="true" name="nivel" id="nivel">
                                                <option value="">Selecione...</option>
                                                <?php
                                                    foreach ($niveis as $value){
                                                        if($value->id == $alterar['nivel'])
                                                            echo "<option selected value='$value->id'>$value->nome</option>";
                                                        else
                                                            echo "<option value='$value->id'>$value->nome</option>";
                                                    }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                               
                                <div class="col-sm-12">
                                    <div class="form-group"> 
                                        <div class=" col-sm-offset-2 col-md-8">
                                            <input type="checkbox" <?php if($alterar['ativo'] == 1){ echo "checked"; } ?> id="remember_me_3" name="ativo" class="filled-in">
                                            <label for="remember_me_3">ATIVO</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-sm-4">
                                        <input type="hidden" name="id_usuario" value="<?=$alterar['id']?>">
                                        <button type="submit" id="editar" class="btn btn-warning m-t-15 waves-effect">EDITAR</button> &nbsp;
                                        <a href="../" class="btn btn-default m-t-15 waves-effect">VOLTAR</a>
                                    </div>
                                </div>   
                            </form>
                        </div>
                    </div>
                    <?php } ?>
                    
                </div>

                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                                CONSULTAR - <span style="font-size:12px">USUÁRIO</span>
                            </h2>
                        </div>
                        <div class="body">
                           <table class="table table-bordered table-striped table-hover js-basic-example dataTable" id="data-table-no-extension" >
		                        <thead>
		                            <tr>
				                      	<th><b>Nome</b></th>
                                        <th><b>Usuário</b></th>
                                        <th><b>Ativo</b></th>
				                      	<th style="max-width:100px"><b>Editar / Excluir</b></th>
		                            </tr>
		                        </thead>
		                        <tbody>
		                        <?php
		                        	foreach ($usuarios as $row) { 

                                        $ativo ="<span class='badge bg-grey'><i class='material-icons'>close</i></span>";

                                        if($row->ativo == 1){
                                            $ativo ="<span class='badge bg-green'><i class='material-icons'>done</i></span>";
                                        }
                                ?>

                        		 		<tr>
                                      		<td><?=strtoupper($row->nome)?></td>
                                            <td><?=strtolower($row->usuario)?></td>
                                            <td><?=$ativo?></td>
                                          	<td>
                                                <a href="<?=base_url()?>usuario/editar/<?=$row->id?>" class="btn btn-primary waves-effect">
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
                                						<form action='<?=base_url()?>usuario/deletar' method='POST'>
                                							<button type='submit' name='id_usuario' value='<?=$row->id?>' class='btn btn-success'>
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

	//inserimos as mascaras nos campos 
    $("#telefone").mask("(99) 9999-9999");

  
    // $("#form1").validate({
    //   rules:{
    //     nome: "required",
    //     telefone: "required",
    //     email:{
    //       required: true,
    //       email: true
    //     },
    //     senha: "required",
    //     conf_senha:{
    //       equalTo: "#senha"
    //     },
    //     conf_senha_up:{
    //       equalTo: "#senha_up"
    //     }
    //   },
    //   messages:{
    //     nome: "Campo em branco!",
    //     telefone: "Campo em branco!",
    //     email:{
    //       required: "Campo em branco!",
    //       email: "Email inválido!"
    //     },
    //     senha: "Campo em branco!",
    //     conf_senha:{
    //       equalTo: "Senha diferente!"
    //     },
    //     conf_senha_up:{
    //       equalTo: "Senha diferente!"
    //     }
    //   }
    // });

    $(document).ready(function(){

        // var departamento = '' + <?=@$alterar['id_departamento']?> + ''; 
        
        // if(departamento != 0){
        //     $.ajax({
        //         url: '<?=base_url();?>' + 'index.php/departamento/getSecretariaByDepartamento',
        //         type: 'POST',
        //         data: {id_departamento: departamento},
        //         dataType: 'html',
        //         beforeSend: function(){

        //         },
        //         success: function(resp) {

        //             $("#secretaria").val(resp);
        //             $("#secretaria").change(); 

        //         },
        //         error: function( req, status, err ) {
        //             //console.log( 'Something went wrong - ' + err + status + req );
        //             console.log(req);
        //         }
        //     });
        // }

        // $("#secretaria").change(function(){
        //     var secretaria = $(this).val();

        //     $(".div_departamento").html("<p style='margin-bottom:20px;'>Não há departamentos</p>");
        //     if(secretaria != ""){
        //         $.ajax({
        //             url: '<?=base_url();?>' + 'index.php/departamento/getDepartamentoBySecretaria',
        //             type: 'POST',
        //             data: {id_secretaria: secretaria},
        //             dataType: 'html',
        //             beforeSend: function(){

        //             },
        //             success: function(resp) {
        //                 $(".div_departamento").html(resp);
        //                 atualizaDepartamento();
        //             },
        //             error: function( req, status, err ) {
        //                 //console.log( 'Something went wrong - ' + err + status + req );
        //                 console.log(req);
        //             }
        //         });
        //     }

        // });

        function atualizaDepartamento()
        {
            if(departamento != '')
                $("body").find("#departamento").selectpicker('val', departamento);
        }

    });

    //Multi-select
    $('#optgroup').multiSelect({ selectableOptgroup: true });
    $('#optgroup2').multiSelect({ selectableOptgroup: true });

</script>