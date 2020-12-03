<?php $this->load->view('header'); ?>
<?php $this->load->view('menu'); ?>

<section class="content">
	<div class="container-fluid">

		<div class="row clearfix">

			<div class="row clearfix">
				<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
					<div class="info-box bg-cyan hover-expand-effect">
						<div class="icon">
							<i class="material-icons">playlist_add_check</i>
						</div>
						<div class="content">
							<div class="text" style="font-size: 15px;">Total de produtos</div>

							<div class="number count-to" data-from="0" data-to="<?php echo $total_estoque[0]->sum ?>" data-speed="1000" data-fresh-interval="20"><?php echo $total_estoque[0]->sum ?></div>
						</div>
					</div>
				</div>
				<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
					<div class="info-box bg-light-green hover-expand-effect">
						<div class="icon">
							<i class="material-icons">add_circle_outline</i>
						</div>
						<div class="content">
							<div class="text" style="font-size: 15px;">Entradas Mensais</div>
							<div class="number count-to" data-from="0" data-to="<?php echo $total_entrada_mes[0]->sum ?>" data-speed="1000" data-fresh-interval="20"><?php echo $total_entrada_mes[0]->sum ?></div>
						</div>
					</div>
				</div>
				<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
					<div class="info-box bg-orange hover-expand-effect">
						<div class="icon">
							<i class="material-icons">exit_to_app</i>
						</div>
						<div class="content">
							<div class="text" style="font-size: 15px;">Saidas Mensais</div>
							<div class="number count-to" data-from="0" data-to="<?php echo $total_saida_mes[0]->sum ?>" data-speed="1000" data-fresh-interval="20"><?php echo $total_saida_mes[0]->sum ?></div>
						</div>
					</div>
				</div>

				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

					<?php echo $this->session->flashdata('result'); ?>

					<div class="card">

						<div class="header">
							<h2>
								ENTRADA / SAÍDA - ESTOQUE CONSULTA
							</h2>

							<input type="hidden" id="tipo_estoque" name="tipo_estoque" value="<?= $tipo_estoque ?>" />
							<ul class="header-dropdown">
								<li>
									<a href="<?php echo base_url() ?>EntradaSaida" class="btn btn-info m-t--15 waves-effect " style="cursor:pointer;">&nbsp;&nbsp;Movimento &nbsp;&nbsp; <i style='color:white;' class="material-icons">open_in_new</i>
									</a>
								</li>
							</ul>
						</div>


						<div class="body">
							<ul class="nav nav-tabs" role="tablist">
								<li role="presentation" class="active">
									<a href="#estoque" data-toggle="tab">
										<i class="material-icons">view_list</i>ESTOQUE
									</a>
								</li>
								<li role="presentation">
									<a href="#entradas" data-toggle="tab">
										<i class="material-icons">view_list</i>ENTRADAS
									</a>
								</li>
								<li role="presentation">
									<a href="#saidas" data-toggle="tab">
										<i class="material-icons">view_list</i>SAÍDAS
									</a>
								</li>
							</ul>


							<!-- Tab panes -->
							<div class="tab-content">
								</br>
								<div role="tabpanel" class="tab-pane fade in active" id="estoque">
									<table class="table table-bordered table-striped table-hover js-basic-example dataTable" id="datatable-estoque">
										<thead>
											<tr>
												<th><b>ID</b></th>
												<th><b>Código</b></th>
												<th><b>Nome Produto</b></th>
												<th><b>Mínima</b></th>
												<th><b>Estoque</b></th>
												<th><b>Diferença</b></th>
												<th><b>Data alteração</b></th>

												<!-- <th><b>Lançar no estoque </b></th> -->
											</tr>
										</thead>
										<tbody>
											<?php
											foreach ($estoques as $row) {
												$quantidade = number_format($row->quantidade, 3, '.', ',');
												$qtd_minima = number_format($row->quantidade, 3, '.', ',');
												$qtd_minima = str_replace('.', ',', $row->qtd_minima);
												$quantidade = str_replace('.', ',', $row->quantidade);

											?>
												<tr>
													<td style='width:7%;'><?= $row->id ?></td>
													<td style='width:10%;'><?= $row->cod_produto ?></td>
													<td style='width:30%;'><?= $row->nome_produto ?></td>
													<td style='width:8%;'><?= $qtd_minima ?></td>
													<td style='width:8%;'><?= $quantidade ?></td>


													<?php if ($row->minimo == NULL) { ?>
														<td style='width:7%; color:green; font-size:14px;'><?= $row->diferenca ?></td>
													<?php   } ?>

													<?php if ($row->minimo == "S") { ?>
														<td style='width:7%; color:red;  font-size:14px;'><?= $row->diferenca ?></td>
													<?php   } ?>
													<td>
														<?= (date('d/m/Y', strtotime($row->ultima_alteracao))) ? date('d/m/Y', strtotime($row->ultima_alteracao)) : '' ?>
													</td>

													<!-- <td style='width:10%;'><?= $row->ultima_alteracao ?></td> -->
												</tr>

											<?php	} ?>
										</tbody>
									</table>
								</div>

								<div role="tabpanel" class="tab-pane fade" id="entradas">
									<table class="table table-bordered table-striped table-hover js-basic-example dataTable" id="datatable-entrada" style="width:100%;">
										<thead>
											<tr>
												<th><b>Produto</b></th>
												<th><b>Quantidade</b></th>
												<th><b>Valor</b></th>
												<th><b>Motivo</b></th>
												<th><b>Observação</b></th>
												<th><b>Lançado em</b></th>
												<th><b>Nº doc</b></th>
												<th></th>
											</tr>
										</thead>
										<tbody>
											<?php

											foreach ($entradas as $row) {
												$valor_entrada = number_format($row->valor_entrada, 2, ',', '.');
												$quantidade = str_replace('.', ',', $row->quantidade);

												$fichas = '';

												foreach ($row->fichas as $ficha) {
													$fichas .= "<p style='margin-top: 3px; font-size: 15px; overflow-wrap: anywhere;'>Ficha $ficha->id</p> <hr><br>";
												}
											?>

												<tr>
													<td><?= $row->nome_produto ?></td>
													<td style="color:darkgreen"><button class="data_modal" id="data_modal" name="data_modal" style="border: none; padding: 0; background:none;" type="button"><?= $quantidade ?></button></td>
													<td><?= $row->valor_entrada ?></td>
													<td><?= $row->nome_motivo ?></td>

													<td><?= $row->observacao ?></td>

													<td>
														<?= (date('d/m/Y', strtotime($row->data_movimento))) ? date('d/m/Y', strtotime($row->data_movimento)) : '' ?>
													</td>
													<td class="doc"><?= $row->doc ?></td>
													<td><?= $fichas ?></td>
												</tr>

											<?php   } ?>
										</tbody>
									</table>
								</div>


								<div role="tabpanel" class="tab-pane fade" id="saidas">
									<table class="table table-bordered table-striped table-hover js-basic-example dataTable" id="datatable-saida" style="width:100%;">
										<thead>
											<tr>
												<th><b>Produto</b></th>
												<th><b>Quantidade</b></th>
												<th><b>Motivo</b></th>
												<!-- <th><b>Solicitante</b></th> -->
												<th><b>Observação</b></th>
												<th><b>Lançado em</b></th>
												<th><b>Nº doc</b></th>
												<th></th>
											</tr>
										</thead>
										<tbody>
											<?php

											foreach ($saidas as $row) {
												$quantidade = str_replace('.', ',', $row->quantidade);

												$fichas = '';

												foreach ($row->fichas as $ficha) {
													$fichas .= "<p style='margin-top: 3px; font-size: 15px; overflow-wrap: anywhere;'>Documento: $ficha->nro_documento - Ficha $ficha->id: $ficha->conteudo</p> <hr><br>";
												}

											?>

												<tr>

													<td><?= $row->nome_produto ?></td>
													<td style="color:red"><button class="data_modal" id="data_modal" name="data_modal" style="border: none; padding: 0; background:none;" type="button"><?= $quantidade ?></button></td>
													<td><?= $row->nome_motivo ?></td>
													<!-- <td><?= $row->nome_solicitante ?></td> -->
													<td><?= $row->observacao ?></td>

													<td>
														<?= (date('d/m/Y', strtotime($row->data_movimento))) ? date('d/m/Y', strtotime($row->data_movimento)) : '' ?>
													</td>
													<td class="doc"><?= $row->doc ?></td>
													<td><?= $fichas ?></td>
												</tr>

											<?php   } ?>
										</tbody>
									</table>

								</div>
								<div class="modal fade" id="defaultModal" tabindex="-1" role="dialog" style="display: none;">
									<div class="modal-dialog" role="document">
										<div class="modal-content">
											<div class="modal-header">
												<h4 class="modal-title" id="defaultModalLabel"> </h4>
												<hr>
											</div>

											<div class="modal-body" id="defaultModalBody">


											</div>
											<div class="modal-footer">
												<button type="button" class="btn btn-link waves-effect" data-dismiss="modal">FECHAR</button>
											</div>
										</div>
									</div>
								</div>
								<div class="modal fade" id="defaultModalEntrada" tabindex="-1" role="dialog" style="display: none;">
									<div class="modal-dialog" role="document">
										<div class="modal-content">
											<div class="modal-header">
												<h4 class="modal-title" id="defaultModalLabelEntrada"> </h4>
												<hr>
											</div>

											<div class="modal-body" id="defaultModalBodyEntrada">


											</div>
											<div class="modal-footer">
												<button type="button" class="btn btn-link waves-effect" data-dismiss="modal">FECHAR</button>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
</section>

<?php $this->load->view('footer'); ?>

<script>
	$(document).ready(function() {
		var tipoestoque = $('#tipo_estoque').val();
		if (tipoestoque != 1) {
			var elems = document.getElementsByClassName("data_modal");
			for (var i = 0; i < elems.length; i++) {
				elems[i].disabled = true;
			}
		}


		$("#datatable-estoque").DataTable({
			"language": {
				"url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Portuguese-Brasil.json"
			},
			"order": [
				[0, "desc"]
			],
			"responsive": true,
		});

		var tableE = $("#datatable-entrada").DataTable({
			"columnDefs": [{
				"targets": [7],
				"visible": false,
				"searchable": false
			}],
			"language": {
				"url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Portuguese-Brasil.json"
			},
			"order": [
				[0, "desc"]
			],
			"responsive": true,
		});


		$('#datatable-entrada').on('click', '.data_modal', function() {

			//$('#defaultModalLabel').get(0).reset();

			// Store current row
			$('#defaultModalEntrada').data('row', $(this).closest('tr'));

			// Show dialog
			$('#defaultModalEntrada').modal('show');
		});

		$('#defaultModalEntrada').on('shown.bs.modal', function(e) {
			// Get row data

			var data = tableE.row($(this).data('row')).data();
			console.log(data);
			// Set initial data
			// $('#defaultModalLabel').text("Fichas doc Nº " + data[5]).focus();
			$('#defaultModalLabelEntrada').text("Fichas doc Nº " + data[6]).focus();
			$('#defaultModalBodyEntrada').html(data[7]);
			//	$('#defaultModalBody').html(data[6]);



		});

		var table = $("#datatable-saida").DataTable({
			"columnDefs": [{
				"targets": [6],
				"visible": false,
				"searchable": false
			}],

			"language": {
				"url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Portuguese-Brasil.json"
			},
			"order": [
				[0, "desc"]
			],
			"responsive": true,
		});


		$('#datatable-saida').on('click', '.data_modal', function() {

			//$('#defaultModalLabel').get(0).reset();

			// Store current row
			$('#defaultModal').data('row', $(this).closest('tr'));

			// Show dialog
			$('#defaultModal').modal('show');
		});

		$('#defaultModal').on('shown.bs.modal', function(e) {
			// Get row data

			var data = table.row($(this).data('row')).data();
			//console.log(data);
			// Set initial data
			$('#defaultModalLabel').text("Fichas doc Nº " + data[5]).focus();
			$('#defaultModalBody').html(data[6]);



		});


	});
</script>

<script>
	$('#quantidade').mask("###0,000", {
		reverse: false
	});
	$('#nro_documento').mask("############0", {
		reverse: true
	});
	$('#valor_entrada').mask("###0,00", {
		reverse: true
	});
	$("#valor_unitario").mask("###0,00", {
		reverse: true
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