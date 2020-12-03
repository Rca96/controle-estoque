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
							ENTRADA / SAÍDA - ESTOQUE CONSULTA
						</h2>
					</div>
					<input id="last_id_ficha" name="last_id_ficha" type="hidden" value="<?php echo $last_id_ficha->id ?>" />
					<div class="body">

						<form id="form1" class="form-horizontal" action="<?= base_url() ?>EntradaSaida/CadastrarMovimento" method="POST" enctype="multipart/form-data">
							<div class="form-group">

								<div class="col-md-12" style="margin-left:30px;">

									<div class="col-md-4">
										<h2 style="font-size:18px;" class="card-inside-title">Escolha o tipo de movimento:</h2>
									</div>

									<div class="col-md-7">
										<div class="demo-checkbox">

											<input onclick="clearValue()" type='radio' name='rd_tipo' class='with-gap' value='rd_entrada' id='rd_entrada' class='filled-in' />
											<label style="font-size:18px;" for='rd_entrada'>Entradas</label>

											<input onclick="clearValue()" type='radio' name='rd_tipo' class='with-gap' checked="checked" value='rd_saida' id='rd_saida' class='filled-in' />
											<label style="font-size:18px;" for='rd_saida'>Saídas</label>

										</div>
									</div>


									<div role="tabpanel" class="tab-pane fade in active" id="entrada no estoque">
										<div class="col-md-12" style="margin-bottom: 0px">

											<div class="form-group" id='divsolicitante'>
												<div class="col-md-12">
													<div class="form-group">
														<label class="col-sm-1 control-label" style="margin-right: 10px;">Solicitante:</label>
														<div class="col-sm-10">
															<div class="form-line">
																<input type="text" class="typeahead form-control pessoa" name="solicitante" id="solicitante" placeholder="Buscar (obrigatorio na saida)" autocomplete="off">
																<input type="hidden" name="pessoa">
															</div>
														</div>
													</div>
												</div>
											</div>

											<div id="div_produto" name="div_produto" class="col-md-12">
												<div class="form-group form-float">
													<label class="col-sm-1 control-label" for="produtos">Produto:</label>
													<div class="col-sm-10">
														<div class="form-line">
															<select class="form-control show-tick div_estoque" data-live-search="true" name="id_produto" id="id_produto">
																<option value="">Selecione o produto...</option>
																<?php
																foreach ($produtos as $row) {
																	echo "
																	<option value='$row->id'>$row->nome</option>";
																}
																?>
															</select>
														</div>

													</div>

												</div>
											</div>

											<div id="motivo_div" name="motivo_div" class="col-md-12">
												<div class="form-group form-float">
													<label class="col-sm-1 control-label" for="produtos">Motivo:</label>
													<div class="col-sm-10">
														<div class="form-line">
															<select class="form-control show-tick div_estoque" data-live-search="true" name="id_motivo" id="id_motivo">
																<option value="">Selecione o produto...</option>
																<?php
																foreach ($motivos as $row) {
																	echo "
																	<option value='$row->id'>$row->nome</option>";
																}
																?>
															</select>
														</div>

													</div>

												</div>
											</div>


										</div>

										<div class="col-md-12">
											<div class="form-group form-float">

												<div class="col-sm-12" id="valores">

													<label class="col-sm-1 control-label" for="valor_unitario">Valor unitário:</label>
													<div class="col-sm-2">
														<div class="form-line">
															<input type="value" name="valor_unitario" id="valor_unitario" class="form-control" placeholder="Não obrigatório">
														</div>
													</div>

													<label class="col-sm-2 control-label" for="valor total">Valor total:</label>
													<div class="col-sm-2">
														<div class="form-line">
															<input type="value" name="valor_entrada" id="valor_entrada" class="form-control" placeholder="Não obrigatório">
														</div>
													</div>

												</div>

												<div class="col-sm-12">
													<label class="col-sm-1 control-label" for="nro_documento">Nº doc.:</label>
													<div class="col-sm-2">
														<div class="form-line">
															<input type="text" name="nro_documento" id="nro_documento" class="form-control" placeholder="Não Obrigatório">
														</div>
													</div>

													<label class="col-sm-2 control-label" for="quantidade">Qtd. movimento:</label>
													<div class="col-sm-2">
														<div class="form-line">
															<input type="value" name="quantidade" id="quantidade" class="form-control" onblur="addFields()">
														</div>
													</div>
													<label class="col-sm-2 control-label">Qtd. em Estoque:</label>
													<div class="col-sm-2">
														<label style='color:green; font-size:17px;' name="qtd_estoque" id="qtd_estoque" class="form-control"></label>
													</div>
												</div>
											</div>
											<div class="form-group form-row" id="add_fichas" name="add_fichas"></div>

											<div style="margin-top: 10px;" class="form-group">
												<label for="nome" class="control-label col-sm-1" style="margin-right: 10px;">Observação:</label>
												<div class="col-sm-8">
													<div class="form-line">
														<textarea class="form-control" maxlength="200" name="observacao" rows="2" placeholder=" Observação (Não é obrigatório)"></textarea>
													</div>
												</div>


												<div class="col-sm-12">
													<button type="submit" id="cadastrar" class="btn btn-success m-t-15 waves-effect">ATUALIZAR ESTOQUE</button>
												</div>

												<input type="hidden" id="tipo_estoque" name="tipo_estoque" value="<?= $tipo_estoque ?>" />
											</div>

										</div>
									</div>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>

			<!-- CONSULTAR -->
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

				<div class="card">
					<div class="header">
						<h2>
							CONSULTAR - <span style="font-size:12px">ESTOQUE</span>
						</h2>
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
											<th><b>Tipo Produto</b></th>

											<th><b>Unidade</b></th>
											<th><b>Quantidade</b></th>
											<th><b>Data alteração</b></th>

											<!-- <th><b>Lançar no estoque </b></th> -->
										</tr>
									</thead>
									<tbody>
										<?php

										foreach ($estoques as $row) {

											$quantidade = str_replace('.', ',', $row->quantidade);
										?>


											<tr>
												<td style='width:7%;'><?= $row->id ?></td>
												<td style='width:10%;'><?= $row->cod_produto ?></td>

												<td style='width:20%;'><?= $row->nome_produto ?></td>
												<td style='width:20%;'><?= $row->nome_tipo ?></td>


												<td style='width:8%;'><?= $row->unidade ?></td>
												<td style='width:10%;'><?= $quantidade ?></td>
												<td>
													<?= (date('d/m/Y', strtotime($row->ultima_alteracao))) ? date('d/m/Y', strtotime($row->ultima_alteracao)) : '' ?>
												</td>
												<!-- <td><?= $row->ultima_alteracao ?></td> -->
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

<script type="text/javascript" src="<?= base_url() ?>js/custom/bootstrap3-typeahead.min.js"></script>



<script>
	$(document).ready(function() {
		var tipoestoque = $('#tipo_estoque').val();
		//console.log(tipoestoque);
		if (tipoestoque != 1) {
			//alert("aa");
			$("#add_fichas").hide();
			$("#motivo_div").hide();

			//	document.getElementById("data_modal").disabled = true;

			var elems = document.getElementsByClassName("data_modal");
			for (var i = 0; i < elems.length; i++) {
				elems[i].disabled = true;
			}
		}
		// if (tipoestoque == 1) {
		// 	$("#div_produto").hide();

		// }


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


		$("input[name='rd_tipo']").click(function() {
			if ($(this).val() == 'rd_saida') {
				$("#valores").hide();
				$("#divsolicitante").show();
				return;
			}

			$("#valores").show();
			$("#divsolicitante").hide();

		});

		//simulando click
		$('#rd_saida').click();


		/// AUTO COMPLETE
		$.get('<?= base_url() ?>' + 'index.php/EntradaSaida/getServidoresJson', function(data) {
			$(".pessoa").typeahead({
				source: data,
				minLength: 2
			});
		}, 'json');

		$('.typeahead').change(function() {
			var current = $(this).typeahead("getActive");
			if (current) {
				$(this).next().next().val(current.matricula);
				return;
			}
			$(this).next().next().val("");
		});

		//  TRAZ A QUANTIDADE DE ESTOQUE 
		$("#id_produto").change(function() {
			var id_produto = $(this).val();
			// var qtd_estoque = $("#qtd_estoque").val();

			if (id_produto != "") {
				$.ajax({
					url: '<?= base_url(); ?>' + 'index.php/EntradaSaida/getVerEstoque',
					type: 'POST',
					data: {
						id_produto: id_produto
					},
					dataType: 'html',
					beforeSend: function() {

					},
					success: function(resp) {
						//	console.log(resp);
						$("#qtd_estoque").html(resp);
					},
					error: function(req, status, err) {
						alert('Ops! Erro ao consultar estoque.');
						//	console.log(req);
					}
				});
			}

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

	function clearValue() {
		document.getElementById('quantidade').value = ''
		$("#quantidade").focus();
		$("#quantidade").blur();
		window.getSelection().removeAllRanges();
	}


	function addFields() {
		//campos entrada
		if ($("#rd_entrada").prop("checked")) {
			// Number of inputs to create

			var number = document.getElementById("quantidade").value;
			// Container <div> where dynamic content will be placed
			var container = document.getElementById("add_fichas");
			// Clear previous contents of the container
			//var id = "<?php $last_id_ficha->id; ?>";
			var id = document.getElementById("last_id_ficha").value;
			if (parseInt(id) == null) {
				parseInt(id) = 0;
			}

			while (container.hasChildNodes()) {
				container.removeChild(container.lastChild);
			}
			for (i = 0; i < number; i++) {
				// Append a node with a random text
				container.appendChild(document.createTextNode("Ficha " + (parseInt(id++) + 1) + ":"));
				// Create an <input> element, set its type and name attributes
				var input = document.createElement("input");
				//input.className = "form-control";
				input.type = "text";
				input.name = "Ficha" + i;
				input.id = "Ficha" + i;
				//input.style.cssText = 'padding-top:3px; margin-top:6px; margin-left:10px; width:80%;'
				//container.appendChild(input);
				// Append a line break 
				container.appendChild(document.createElement("br"));

			}
			//var verifica_conteudo = ["Ficha" + i].value;
		}

		//campos saida
		if ($("#rd_saida").prop("checked")) {
			// Number of inputs to create

			var number = document.getElementById("quantidade").value;
			// Container <div> where dynamic content will be placed
			var container = document.getElementById("add_fichas");
			// Clear previous contents of the container
			//var id = "<?php $last_id_ficha->id; ?>";
			var id = document.getElementById("last_id_ficha").value;


			while (container.hasChildNodes()) {
				container.removeChild(container.lastChild);
			}
			for (i = 0; i < number; i++) {
				// Append a node with a random text
				container.appendChild(document.createTextNode("Ficha:"));
				// Create an <input> element, set its type and name attributes
				var input = document.createElement("input");
				var input_ficha = document.createElement("input");
				//input.className = "form-control";
				input_ficha.type = "number";
				input_ficha.name = "ficha_numero" + i;
				input_ficha.id = "ficha_numero" + i;
				input.type = "text";
				input.name = "Ficha" + i;
				input.id = "Ficha" + i;
				input.style.cssText = 'padding-top:3px; margin-top:6px; margin-left:10px; width:60%;'
				input_ficha.style.cssText = 'padding-top:3px; margin-top:6px; margin-left:10px; width:20%;'
				container.appendChild(input_ficha);
				container.appendChild(input);
				// Append a line break 
				container.appendChild(document.createElement("br"));
			}
			var verifica_conteudo = ["Ficha" + i].value;
		}


	}
</script>