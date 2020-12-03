  //Variaveis usadas dentro do código
  //valor do raio
  var raio;
  //variavel para criar circulo a partir de um centro
  var myCity, myCityDesenho, isvisible = false;
  //mapa
  var map;
  //usado para montar os marcadores
  var marker2;
  //marcadores em volta do ponto escolhido
  var markers = new Array();
  //ip que esta acessando
  var ipusu;
  //organizção(provedor) responsavel pelo ip
  var org;
  //dados vindo do banco, com a localização
  var locations2 = "";
  //dados para montar tabela
  var dadosf2 = "";
  //id da cidade
  var idc;
  //latitude e longitude da cidade
  var latlngLate;
  //usado para marcar a cidade escolhida com um marcador diferente
  var customerMarker;
  // grupo de markers
  var markerCluster = new MarkerClusterer();
  //array para mapa de calor
  var heatmapData = new Array();
  //usado para instanciar mapa de calor
  var heatmap;
  //filtrar por secretaria
  var secretaria = null;

  $(document).ready(function(){

    $('#raio_acao').click(function(){
      var raio= $('#raio').val();
      if($.isNumeric(raio))
        $("#raio_atual").html(raio);
      else
        alert("Digite apenas números!");
    });

    MontaMapa(-22.3519957, -47.3520484);

    // simula click no mapa para carregar pontos
    google.maps.event.trigger(map, 'click', {
      stop: null,
      latLng: new google.maps.LatLng(-22.3519957, -47.3520484)
    });

    $("body").on('click', '.secretaria', function(){
      secretaria = $(this).attr('data-val');
      if($(this).attr('style') == 'color:blue;font-weight:bold')
        secretaria = null;

      getData();
    });

    $("body").on('click', '.total_secs', function(){
      secretaria = null;
      $('.secretaria').attr('style', '');
      getData();
    });

  });


  function success(position) {
      var s = document.querySelector('#status');

      if (s.className == 'success') {
          return;
      }

      s.innerHTML = "";
      s.className = 'success';
      var lati = position.coords.latitude;
      var longi = position.coords.longitude;
      MontaMapa(lati, longi);
  }

  //Monta o mapa
  function MontaMapa(latitude, longitude){

    var latlng = new google.maps.LatLng(latitude, longitude);
    latlngLate = latlng;
    var myOptions = {
        zoom: 13.6,
        center: latlng,
        mapTypeControl: true,
        mapTypeControlOptions: {
            style: google.maps.MapTypeControlStyle.SMALL,
            position: google.maps.ControlPosition.TOP_RIGHT
        },
        mapTypeIds: [
          google.maps.MapTypeId.ROADMAP,
          google.maps.MapTypeId.SATELLITE
        ],
        streetViewControl: false,
    };

    map = new google.maps.Map(document.getElementById("mapa"), myOptions);

    google.maps.event.addListener(map, 'click', function(event) {
      placeCustomerMarker(event.latLng);
    });
      
    var marker;

    myCity = new google.maps.Circle({
        center: latlngLate,
        radius: 30000,
        strokeColor: "#0000FF",
        strokeOpacity: 0.8,
        strokeWeight: 2,
        fillColor: "#0000FF",
        fillOpacity: 0.4
    });
    myCity.setMap(map);
    myCity.setVisible(false);
    //getLocations(latitude,longitude);

    //PONTOS & CALOR
    var centerControlDiv = document.createElement('div');
    var centerControl = new CenterControl(centerControlDiv, map);
    centerControlDiv.index = 1;
    map.controls[google.maps.ControlPosition.RIGHT_CENTER].push(centerControlDiv);

    //EXIBIR RAIO
    var topRightControlDiv2 = document.createElement('div');
    var centerControl = new TopRightControl2(topRightControlDiv2, map);
    topRightControlDiv2.index = 2;
    map.controls[google.maps.ControlPosition.TOP_RIGHT].push(topRightControlDiv2);

  }

  function CenterControl(controlDiv, map) {

    // Set CSS for the control border.
    var controlUI = document.createElement('div');
    controlUI.style.backgroundColor = '#1a1a1a';
    controlUI.style.border = '2px solid #1a1a1a';
    controlUI.style.borderRadius = '10px 0px 0px 10px';
    controlUI.style.boxShadow = '0 2px 6px rgba(0,0,0,.3)';
    controlUI.style.cursor = 'pointer';
    controlUI.style.marginBottom = '22px';
    controlUI.style.textAlign = 'left';
    controlUI.title = 'Escolha o tipo de mapa';
    controlDiv.appendChild(controlUI);

    // Set CSS for the control interior.
    var controlText = document.createElement('div');
    controlText.style.color = 'rgb(255, 255, 255)';
    controlText.style.fontFamily = 'Roboto,Arial,sans-serif';
    controlText.style.fontSize = '16px';
    controlText.style.lineHeight = '38px';
    controlText.style.paddingLeft = '5px';
    controlText.style.paddingRight = '5px';
    controlText.innerHTML = '<div class="demo-radio-button">'+
                              '<input name="rd_auto" id="chk_auto" class="filled-in" checked="checked" type="checkbox"><label style="min-width: 100px;font-size:12px" for="chk_auto">Automático</label><br>' + 
                              '<input name="rd_tipo_mapa" id="rd_pontos" class="with-gap radio-col-red" checked="checked" type="radio"><label style="min-width: 100px;" for="rd_pontos">PONTOS</label><br>' + 
                              '<input name="rd_tipo_mapa" id="rd_calor" class="with-gap radio-col-blue" type="radio"><label style="min-width: 100px;" for="rd_calor">CALOR</label>' + 
                            '</div>';

    controlUI.appendChild(controlText);

    // Setup the click event listeners
    controlUI.addEventListener('click', function() {
      getData();
    });

    // altera entre pontos e calor automático
    setInterval(function(){ 
      if($('#chk_auto').is(':checked'))
      {
        if($('#rd_pontos').is(':checked'))
        {
          $('#rd_pontos').prop('checked', false);
          $('#rd_calor').prop('checked', true);
        }
        else
        {
          $('#rd_calor').prop('checked', false);
          $('#rd_pontos').prop('checked', true);
        }

        getData();
      }

    }, 30000);
    

  }

  function CenterLeftControl(controlDiv, data, secretaria) {

    var controlUI = document.createElement('div');
    controlUI.style.backgroundColor = '#fff';
    controlUI.style.border = '2px solid #fff';
    controlUI.style.borderRadius = '10px 0px 0px 10px';
    controlUI.style.boxShadow = '-1px 2px 4px rgba(0, 0, 0, 0.2)';
    controlUI.style.cursor = 'pointer';
    controlUI.style.margin = '0px';
    controlUI.style.textAlign = 'left';
    controlUI.title = 'Estatísticas';
    controlDiv.appendChild(controlUI);

    // Set CSS for the control interior.
    var controlText = document.createElement('div');
    controlText.style.color = 'rgb(0, 0, 0)';
    controlText.style.fontFamily = 'Roboto,Arial, sans-serif';
    controlText.style.fontSize = '12px';
    // controlText.style.fontWeight = '500';
    controlText.style.padding = '10px';
    controlText.innerHTML = '<p style="font-weight:bold;font-size:12px">Números por Secretaria:</p>';

    var total = 0;
    var style = "";
    $.each(data, function(i, val) {
      total += parseInt(val.qtd);

      style = (secretaria == val.id) ? "color:blue;font-weight:bold" : ""; 
      controlText.innerHTML += '<p><a style="'+style+'" class="secretaria" data-val="' + val.id + '">' + val.nome + ' (' + val.qtd + ')' + '</a></p>';
    });

    controlText.innerHTML += '<p style="margin-top:15px"><a class="total_secs">TOTAL: ' + total + '</a></p>';
    controlUI.appendChild(controlText);

  }

  function TopRightControl2(controlDiv, map) {

    // Set CSS for the control border.
    var controlUI = document.createElement('div');
    controlUI.style.backgroundColor = '#fff';
    controlUI.style.border = '2px solid #fff';
    controlUI.style.borderRadius = '3px';
    controlUI.style.boxShadow = '-1px 2px 4px rgba(0, 0, 0, 0.2)';
    controlUI.style.cursor = 'pointer';
    controlUI.style.margin = '10px';
    controlUI.style.textAlign = 'center';
    controlUI.title = 'Exibir Raio';
    controlDiv.appendChild(controlUI);

    // Set CSS for the control interior.
    var controlText = document.createElement('div');
    controlText.style.color = 'rgb(0, 0, 0)';
    controlText.style.fontFamily = 'Roboto,Arial,sans-serif';
    controlText.style.fontSize = '11px';
    controlText.style.fontWeight = '500';
    controlText.style.padding = '6px';
    controlText.innerHTML = 'Exibir raio';
    controlUI.appendChild(controlText);

    // Setup the click event listeners
    controlUI.addEventListener('click', function() {
      
          if(!isvisible)
            isvisible = true;
          else
            isvisible = false;

          myCityDesenho.setVisible(isvisible);
    });

  }

  function getData(){

    var data = { 
      'status[]' : [],
      'data_ini': $("#data_ini").val(),
      'data_fim': $("#data_fim").val(),
      'hora_inicial': $("#hora_inicial").val(),
      'hora_final': $("#hora_final").val(),
      'agrupamento': 'secretaria',
      'secretaria': secretaria
    };

    $(".chk_status").each(function() {
      if($(this).is(":checked"))
        data['status[]'].push($(this).val());
    });


    $.ajax({
        type: "POST",
        data: data,
        url: '../index.php/mapa/getMarkers',
        dataType: "JSON",
        beforeSend: function(){
          $("#page-content-wrapper").attr("class", "loading-overlay");
        },
        success: function(res){
          // console.log(res);
          //limpa marcadores e calor
          clearHeatmap();
          clearMarcadores();
          //clearEstatisticas();

          if($('#rd_calor').is(':checked'))
            montaCalor(res.ocorrencias);
          else
            montaPontos(res.ocorrencias);

          montaEstatistica(data);
          
          $("#page-content-wrapper").attr("class", "");
        },
        error: function(error){
          $("#page-content-wrapper").attr("class", "");
          console.log(error);
        }
    });

    
  }
  
  function montaPontos(data){
    myCity.setVisible(false);
    myCityDesenho.setVisible(false);

      var infowindow = new google.maps.InfoWindow();
      var latLngBounds = myCity.getBounds();
      //array para gerar pdf's dos pontos montados
      var pontos_no_raio= new Array();

      for (var i = 0; i < data.length; i++){

        raio = new google.maps.LatLng(data[i].latitude, data[i].longitude);
        if (latLngBounds.contains(raio)) {

            //console.log(data[i]);
            //adicionando markers
            marker2 = new google.maps.Marker({
                position: new google.maps.LatLng(data[i].latitude, data[i].longitude),
                map: map,
                icon: "../images/pins/"+data[i].pin,
                id: data[i].id,
                title: data[i].problema,
                desc: data[i].descricao,
                foto: data[i].foto,
                data_ocorrencia: data[i].data_ocorrencia,
                hora_ocorrencia: data[i].hora_ocorrencia,
                protocolo: data[i].protocolo
            });

            markers.push(marker2);

            google.maps.event.addListener(marker2, 'click', (function(marker2, i){
                return function(){
                    //Coloca nome da cidade no conteudo da InfoWindow
                    var hora_ocorrencia = "";
                    if(data[i].hora_ocorrencia != "" && data[i].hora_ocorrencia != null)
                      hora_ocorrencia = " às " + data[i].hora_ocorrencia;

                    // var anexo = "";
                    // if(data[i].anexo != "" && data[i].anexo != null)
                    //   anexo = data[i].anexo;

                    var contentString = '<a href="../ocorrencia/ver/'+data[i].id+'" target="_blank" style="outline:none;">'+
                                          '<div id="gmap-content">'+
                                              '<div id="gmap-siteNotice">'+
                                              '</div>'+
                                              '<h3 id="gmap-firstHeading" class="gmap-firstHeading" style="margin-top:10px">'+data[i].problema+'</h3>'+
                                              '<div id="gmap-bodyContent">'+
                                                  '<div class="col-sm-3 text-center" style="padding:0px 10px 0px 0px;">'+
                                                      '<img src="'+data[i].foto+'" style="max-width:100%;max-height:65px">'+
                                                  '</div>'+
                                                  '<div class="col-sm-9" style="padding:0px">'+
                                                      '<b>Endereço: </b>' +
                                                        data[i].endereco+'<br>'+
                                                      '<b>Protocolo: </b> '+data[i].protocolo+'<br>'+
                                                      '<b>Observação: </b> '+data[i].descricao+'<br>'+
                                                      '<b>Ocorrido em: </b>'+
                                                        data[i].data_ocorrencia + hora_ocorrencia +'<br>'+
                                                  '</div>'+
                                              '</div>'+
                                          '</div>'
                                        '</a>';

                    infowindow.setContent(contentString);
                    //seta o marcador certo a ser exibido
                    infowindow.open(map, marker2);
          
                }
            })(marker2, i));

            pontos_no_raio.push(data[i]);
        }
      }
      
      // style marker cluster
      var mcOptions = {
                        gridSize: 1, 
                        maxZoom: 200, 
                        zoomOnClick: false,
                        imagePath: '../images/m'
                      };
      //instance makerClusterer
      markerCluster = new MarkerClusterer(map, markers, mcOptions);

      google.maps.event.addListener(markerCluster, "clusterclick", function (cluster){

        var contentString2 = '<div id="gmap-content">'+
                              '<div id="gmap-siteNotice">'+
                              '</div>'+
                              '<span style="font-size:13px;font-weight: 300;font-family: Roboto,Arial,sans-serif;">Ocorrências: </span>'+
                              '<div id="gmap-bodyContent">'+
                                  '<p>'+
                                      '<b>Quantidade de Ocorrências: </b> '+cluster.getSize()+'<br>'+
                                      '<a data-toggle="modal" data-target="#modal_ocorr" style="font-size:12px;cursor:pointer;">Visualizar ocorrências</a><br>'+
                                  '</p>'+
                              '</div>'+
                              '</div>';

        // $('.modal-title').html(cluster.getMarkers()[0].getTitle());
        $('.modal-title').html("Ocorrências neste ponto");

        var qtd= cluster.getSize();

        $('#table-ocorrencias').html("");
        $('#table-ocorrencias').append('<tr style="font-weight:bold;">'+
                                    '<th>Foto</th>'+
                                    '<th>Problema</th>'+
                                    '<th>Protocolo</th>'+
                                    '<th>Observação</th>'+
                                    '<th>Ocorrido em</th>'+
                                   '</tr>');

        for (var i = 0; i < qtd; i++){

          $('#table-ocorrencias').append('<tr>'+
                                          '<td><img src="'+cluster.getMarkers()[i].foto+'" style="max-width:100%;max-height:60px"></td>'+
                                          '<td>'+
                                            '<a href="../ocorrencia/ver/'+cluster.getMarkers()[i].id+'" target="_blank" style="color: #337ab7;">'+cluster.getMarkers()[i].title+'</a>'+
                                          '</td>'+
                                          '<td>'+cluster.getMarkers()[i].protocolo+'</td>'+
                                          '<td>'+cluster.getMarkers()[i].desc+'</td>'+
                                          '<td>'+cluster.getMarkers()[i].data_ocorrencia + " " + cluster.getMarkers()[i].hora_ocorrencia + '</td>'+
                                        '</tr>'
                                  );
        }
        

        infowindow.setContent(contentString2);
        infowindow.setPosition(cluster.getCenter());
        infowindow.open(map);
      });  


      var pontos= JSON.stringify( pontos_no_raio );

      // gerar o PDF
      // $("#table").html("<form method='POST' action='gerapdf.php'>"+
      //     "<input type='hidden' name='dados' value='"+pontos+"' />"+
      //      "<input type='hidden' name='raio_acao' value='"+$('#raio_atual').html()+"' />"+
      //      "<input type='hidden' name='ano' value='"+$('#ano').val()+"' />"+
      //     "<input type='submit' style='margin-bottom:20px;' class='btn btn-warning'  id='raio_acao' value='Gerar PDF' />"+
      //   "</form>");

      if(pontos_no_raio=="")
        $("#table").html("");

       montaImagem(pontos);
  
  }

  function montaCalor(data){
    myCity.setVisible(false);

    var latLngBounds = myCity.getBounds();
    for (var i = 0; i < data.length; i++){
      raio = new google.maps.LatLng(data[i].latitude, data[i].longitude);
      if (latLngBounds.contains(raio)) {
        heatmapData[i] = new google.maps.LatLng(data[i].latitude, data[i].longitude);
      }
    }

    heatmap = new google.maps.visualization.HeatmapLayer({
      data: heatmapData,
      radius: 50
    });
    heatmap.setMap(map);
  }

  function montaEstatistica(data){

    $.ajax({
      type: "POST",
      data: data,
      url: '../index.php/mapa/getQtdByAgrupamento',
      dataType: "JSON",
      beforeSend: function(){
        //$("#page-content-wrapper").attr("class", "loading-overlay");
      },
      success: function(res){

        if(map.controls[google.maps.ControlPosition.RIGHT_CENTER].length >= 2)
          map.controls[google.maps.ControlPosition.RIGHT_CENTER].pop();

        //ESTATISTICAS
        var centerLeftControlDiv = document.createElement('div');
        var centerLeftControl = new CenterLeftControl(centerLeftControlDiv, res, data.secretaria);
        centerLeftControlDiv.index = 2;
        map.controls[google.maps.ControlPosition.RIGHT_CENTER].push(centerLeftControlDiv);
        
        //$("#page-content-wrapper").attr("class", "");
      },
      error: function(error){
        //$("#page-content-wrapper").attr("class", "");
        console.log(error);
      }
    });
    // var total = 0;
    // $.each(data, function(i, val) {

    //   $("#table_ocorrencias_est").DataTable().row.add({
    //     "0" : "<img src='images/icones/" + val.icone + "'> " + val.nome + "</td>",
    //     "1" : val.total
    //   }).draw();

    //   total += val.total;
    // });
    
    // //Fill modal informations
    // $(".span_total").html(total);
    // $(".span_raio").html(($("#raio").val()/1000) + " km");
    // $(".span_de").html($("#data_ini").val());
    // $(".span_ate").html($("#data_fim").val());
    // $(".span_instituicao").html($("#instituicao option:selected").text());

  }


  function error(msg) {
    var s = document.querySelector('#status');
    s.innerHTML = typeof msg == 'string' ? msg : "falhou";
    s.className = 'fail';
  }

  function VerificaLoc(){
    if (navigator.geolocation){
        navigator.geolocation.getCurrentPosition(success, error);
    } else {
        error('Seu navegador não suporta <b style="color:black;background-color:#ffff66">Geolocalização</b>!');
    }
  }

  //Tabela para mostrar os dados da cidade que foi clicada
  function GenerateTable(){
    //adiciona os dados a um array para que seja colocado na tabela
    var dadosf = new Array();
    dadosf.push(["Empresa", "Faixa", "Tecnologia", "Conexoes"]);
    var dadosbd = dadosf2.split("_");
    var cont = 0;
    for (cont = 0; cont < dadosbd.length; cont++) {
        dadosf.push(dadosbd[cont].split(","));
    }

    //cria a tabela
    var table = document.createElement("TABLE");
    //recebe o botao para o pdf
    var botao = document.getElementById("btnpdf");
    table.border = "1";

    
    var columnCount = dadosf[0].length;

    
    var row = table.insertRow(-1);
    for (var i = 0; i < columnCount; i++) {
        var headerCell = document.createElement("TH");
        headerCell.innerHTML = dadosf[0][i];
        row.appendChild(headerCell);
    }

    //adiciona os dados as linhas
    for (var i = 1; i < dadosf.length - 1; i++) {
        row = table.insertRow(-1);
        for (var j = 0; j < columnCount; j++) {
            var cell = row.insertCell(-1);
            cell.innerHTML = dadosf[i][j];
        }
    }

    var dvTable = document.getElementById("dvTable");
    dvTable.innerHTML = "";
    botao.style.visibility="visible";
    //document.getElementById("btnpdf").style.visibility = "hidden";
    dvTable.appendChild(table);
    var posiTable = $("#btnpdf");
    var offset = posiTable.offset();
    $(window).scrollTop(offset.top, offset.left);
  }
  
  //Marker diferente para diferenciar a onde pessoa esta/ou clicou
  function placeCustomerMarker(location){
    //chama a fução para limpar o mapa e deixar sem marcadores
    clearMarcadores();
    clearHeatmap();
    if (customerMarker != null) {
      customerMarker.setPosition(location);
    }else{
        //cor do marcador
        var pinColor = "008CD5";
        //usando imagem de um icone
        //var image = 'images/marcador.png';
        //busca o marcador ja pronto com a cor escolhida e setando seu tamanho
        var pinImage = new google.maps.MarkerImage("http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld=%E2%80%A2|" + pinColor, new google.maps.Size(
          21, 34));
        customerMarker = new google.maps.Marker({
        title: "Você está aqui!",
        position : location,
        map : map
      });
    }
    latlngLate = location;

    //getLocationsLate(latlngLate.A,latlngLate.F);
    //Limpa o circulo para ser adicionado o novo raio
    $('#raio_atual').html($('#raio').val());

    if($('#raio').val()==""){
       $('#raio').val('10000');
      $('#raio_atual').html('10000');
    }
     

    myCity.setMap(null);
    myCity = new google.maps.Circle({
            center: latlngLate,
            radius: parseInt($('#raio').val()),
            strokeColor: "#0000FF",
            strokeOpacity: 0.8,
            strokeWeight: 2,
            fillColor: "#0000FF",
            fillOpacity: 0.1,
            map: map
        });
      myCity.setVisible(false);
      
      //MONTA PONTOS PARA DESENHO
      if(myCityDesenho)
          myCityDesenho.setMap(null);

      myCityDesenho = new google.maps.Circle({
            center: myCity.getCenter(),
            radius: parseInt($('#raio').val()) + (parseInt($('#raio').val())/100)*25,
            strokeColor: "#0000FF",
            strokeOpacity: 0.8,
            strokeWeight: 2,
            fillColor: "#0000FF",
            fillOpacity: 0.1,
            map: map
        });

    //chama função para montar os pontos ou mapa de calor de acordo com o local escolhido
    getData();
    //montaPontos();

  }

  function montaImagem(pontos){
    var latlng;
    var markers="";
    var tipo;
    $.each(JSON.parse(pontos), function(i, data){

        //console.log(data);

        icon_tipo= "http://189.56.68.34:3470/guardapma/images/"+data.imagem;

        markers = markers.concat("markers=icon:"+icon_tipo+"|"+data.latitude+","+data.longitude+"&");

    });

    $(".gerar-imagem").attr("href", "https://maps.googleapis.com/maps/api/staticmap?"+
                                    "center="+latlngLate+"&"+
                                    "zoom=14&"+
                                    "size=640x400&"+
                                    "scale=1&"+
                                    "maptype=roadmap&"+markers
                            );
  }

//limpa os marcadores
function clearMarcadores() {

  for (var i = 0; i < markers.length; i++ ) {
    markers[i].setMap(null);
  }
  
  markers.length = 0;
  markerCluster.clearMarkers();

}

//limpa as variacoes de calor
function clearHeatmap() {

  heatmapData = [];
  if(heatmap)
    heatmap.setMap(null);

}

function clearEstatisticas(){
  $("#table_ocorrencias_est").DataTable().clear().draw();
}

function gerapdf(){
window.open("gerapdf.php?id=" + $("#idpdf").val());


}
