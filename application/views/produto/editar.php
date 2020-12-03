<?php $this->load->view('header'); ?>
<?php $this->load->view('menu'); ?>

<section class="content">
	<div class="container-fluid">
		<div class="row clearfix">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

				<?php echo $this->session->flashdata('result'); ?>

				<div class="card">
					<div class="header">
						<h2>
							EDITAR - <span style="font-size:12px">PRODUTO (<b><?= $alterar['nome_produto'] ?></b>)</span>
						</h2>
					</div>
					<div class="body">
						<form id="form1" class="form-horizontal" action="<?= base_url() ?>produto/alterar" method="POST" enctype="multipart/form-data">
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<div class="form-group">
									<label class="col-sm-2 control-label" for="cod_produto">Codigo Produto:</label>
									<div class="col-sm-3">
										<div class="form-line">
											<input type="text" class="form-control" value="<?= $alterar['cod_produto'] ?>" name="cod_produto" id="nome_produto" placeholder="Digite o tipo de produto">

											<input type="hidden" id="id" name="id" value="<?= $alterar['id'] ?>" />
										</div>
									</div>


								</div>

								<div class="form-group">
									<label class="col-sm-2 control-label" for="nome_produto">Nome:</label>
									<div class="col-sm-8">
										<div class="form-line">
											<input type="text" class="form-control" value="<?= $alterar['nome_produto'] ?>" name="nome_produto" id="nome_produto" placeholder="Digite o tipo de produto">
										</div>
									</div>
								</div>

								<div class="form-group">
									<label class="col-sm-2 control-label" for="nome">Unidade do material:</label>
									<div class="col-sm-3">
										<select class="form-control show-tick" data-live-search="true" name="unidade" id="unidade">
											<option value="">Selecione...</option>

											<?php
											foreach ($unidades as $value) {
												if ($value->id == $alterar['id_unidade'])
													echo "<option selected value='$value->id'>$value->nome</option>";
												else
													echo "<option value='$value->id'>$value->nome</option>";
											}
											?>

										</select>
									</div>

									<label class="col-sm-2 control-label" for="nome">Tipo produtos:</label>
									<div class="col-sm-3">
										<select class="form-control show-tick" data-live-search="true" name="tipoprodutos[]" id="tipoprodutos" multiple="multiple">

											<?php
											$tipo = explode("|", $alterar['id_tipo_produto']);
											foreach ($tipos_produtos as $value) {

												$qtd_minima = str_replace('.', ',', $row->qtd_minima);
												$qtd_maxima = str_replace('.', ',', $row->qtd_maxima);
												$qtd_estoque = str_replace('.', ',', $row->qtd_estoque);



												if (in_array($value->id, $tipo))
													echo "<option selected value='$value->id'>$value->nome</option>";
												else
													echo "<option value='$value->id'>$value->nome</option>";
											}

											?>

										</select>

									</div>
								</div>
							</div>

							<div class="form-group">

								<label class="col-md-2 control-label" for="nome">Qtd Mínima: <span class="required"></span></label>
								<div class="col-md-2">
									<input type="text" id="qtd_minima" name="qtd_minima" value="<?= $alterar['qtd_minima'] ?>" required maxlength="10" class="form-control form-line" />
								</div>

								<label class="col-md-2 control-label" for="nome">Qtd Maxima: <span class="required"></span></label>
								<div class="col-md-2">
									<input type="text" id="qtd_maxima" name="qtd_maxima" value="<?= $alterar['qtd_maxima'] ?>" required maxlength="10" class="form-control form-line" />
								</div>
								<label class="col-sm-2 control-label" for="qtd_estoque">Estoque:</label>
								<div class="col-sm-2">
									<div class="form-line">
										<input style="font-size:17px;" type="text" class="form-control" value="<?= $alterar['qtd_estoque'] ?>" disabled="disabled" name="qtd_estoque" id="qtd_estoque">

									</div>
								</div>
							</div>


							<div class="form-group">
								<label for="nome" class="control-label col-sm-2">Observação:</label>
								<div class="col-sm-8">
									<div class="form-line">
										<textarea class="form-control" maxlength="200" name="observacao" rows="2" placeholder="Se houver observações, digite aqui" style="resize:none"><?= $alterar['observacao'] ?></textarea>
									</div>
								</div>

							</div>
							<input style="padding-bottom: 10px; padding-left: 20px" name="userfile[]" id="userfile" type="file" multiple="multiple" />


							<div class="col-md-12">
								<table class="table table-bordered table-striped table-hover js-basic-example" style="font-size:12px" id="consulta_licitacoes">
									<thead>
										<tr>
											<th><b>Anexo</b></th>
										</tr>
									</thead>
									<tbody>
										<?php foreach ($alterar['fotos'] as $anexo) { ?>
											<tr>
												<td><i class="material-icons">attach_file</i></td>
												</td>
												<td><a target="_blank" href="<?= base_url() . 'uploads/' ?><?= $anexo->path ?>"><?= $anexo->path ?></td>
												<td style="display:none;"><?= $anexo->id ?></td>
												<td style="display:none;"><?= $anexo->id_produto ?></td>
												<td>
													<button type="button" class="btn btn-danger waves-effect" data-trigger="focus" data-container="body" data-toggle="popover" data-placement="top" data-original-title="Confirmar exclusão?" data-content="
															<form action='<?= base_url() ?>produto/excluirAnexo' method='POST'>
																<input type='hidden' id='id_produto' name='id_produto' value='<?= $anexo->id_produto ?>'/>
																<input type='hidden' id='id_arquivo' name='id_arquivo' value='<?= $anexo->id ?>'/>
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
											</tr>
										<?php } ?>
									</tbody>
								</table>
							</div>


							<div class="form-group">
								<div class="col-sm-4">
									<input type="hidden" id="id" name="id" value="<?= $alterar['id'] ?>" />
									<button type="submit" id="editar" class="btn btn-warning m-t-15 waves-effect">ALTERAR</button> &nbsp;
									<a href="../" class="btn btn-default m-t-15 waves-effect">VOLTAR</a>
								</div>
							</div>
					</div>
					</form>
				</div>
			</div>
		</div>
	</div>
	</div>
</section>

<?php $this->load->view('footer'); ?>

<script>
	$('#quantidade').mask("###00000", {
		reverse: true
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

		$('#cod_produto').keypress(function(e) {
			var regex = new RegExp("^[ A-Z-a-z-0-9]+$");

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
	});
</script>