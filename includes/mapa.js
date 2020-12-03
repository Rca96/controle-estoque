var geocoder;
var map;
var marker;

function initialize(){

    var latlng = new google.maps.LatLng(-22.3604911, -47.37983910000003);
	var options = {
		zoom: 5,
		center: latlng,
		mapTypeId: google.maps.MapTypeId.ROADMAP,
        mapTypeControl: true,
	};
	
	map = new google.maps.Map(document.getElementById("mapa"), options);
	
	geocoder = new google.maps.Geocoder();
	
	marker = new google.maps.Marker({
		map: map,
	});
	
    	marker.setPosition(latlng);
	
	    map.setZoom(17);
	    map.setCenter(marker.getPosition());

}

    function loadMap(latLng)
    {
        console.log(latLng);
        geocoder.geocode({ 'latLng': latLng }, function (results, status) {
            if (status == google.maps.GeocoderStatus.OK) {
                if (results[0]) {
                    
                    var rua = "";
                    var bairro = "";
                    var cidade = "";
                    var uf = "";

                    var qtd = results[0].address_components.length;

                    if(qtd == 8){

                        rua = results[0].address_components[1].long_name ? results[0].address_components[1].long_name : "";
                        bairro = results[0].address_components[2].long_name ? results[0].address_components[2].long_name : "";
                        cidade = results[0].address_components[3].long_name ? results[0].address_components[3].long_name : "";
                        uf = results[0].address_components[5].short_name ? results[0].address_components[5].short_name : "";
                    }
                    else if(qtd == 7){

                        rua = results[0].address_components[1].long_name ? results[0].address_components[1].long_name : "";
                        bairro = results[0].address_components[2].long_name ? results[0].address_components[2].long_name : "";
                        cidade = results[0].address_components[3].long_name ? results[0].address_components[3].long_name : "";
                        uf = results[0].address_components[4].short_name ? results[0].address_components[4].short_name : "";
                        
                    }
                    else if(qtd == 6){

                        rua = results[0].address_components[0].long_name ? results[0].address_components[0].long_name : "";
                        bairro = results[0].address_components[1].long_name ? results[0].address_components[1].long_name : "";
                        cidade = results[0].address_components[2].long_name ? results[0].address_components[2].long_name : "";
                        uf = results[0].address_components[3].short_name ? results[0].address_components[3].short_name : "";

                    }
                    else if(qtd == 5){

                        rua = results[0].address_components[0].long_name ? results[0].address_components[0].long_name : "";
                        bairro = results[0].address_components[1].long_name ? results[0].address_components[1].long_name : "";
                        cidade = results[0].address_components[1].long_name ? results[0].address_components[1].long_name : "";
                        uf = results[0].address_components[3].short_name ? results[0].address_components[3].short_name : "";

                    }
                    else if(qtd == 4){

                        rua = results[0].address_components[0].long_name ? results[0].address_components[0].long_name : "";
                        bairro = results[0].address_components[1].long_name ? results[0].address_components[1].long_name : "";
                        cidade = results[0].address_components[1].long_name ? results[0].address_components[1].long_name : "";
                        uf = results[0].address_components[2].short_name ? results[0].address_components[2].short_name : "";
                    }

                    var cep = rua + ", " + bairro + ", " + cidade;

                    console.log(results[0].address_components);
                    
                    //Consulta o webservice viacep.com.br/
                    $.getJSON("//viacep.com.br/ws/"+ uf +"/" + cidade +"/"+ rua +"/json/", function(dados) {

                        if (!("erro" in dados)) {
                            $("#mapa").fadeOut("slow");
                            $("#carregar" ).html("<p id='carregar'><img src='../images/loading.gif'></p>");
                        }
                        else {
                            //CEP pesquisado não foi encontrado.
                            alert("CEP não encontrado.");
                        }
                    }).done(function(dados){

                        $("#carregar" ).html("<p id='carregar'></p>");
                        var posiMapa = $("#mapa");
                        var offset = posiMapa.offset();
                        $("#mapa").fadeIn("slow");

                        if(dados.length > 0){
                            if(dados[0].cep == null){
                                alert("CEP não encontrado.");
                            }else{
                                //Atualiza os campos com os valores da consulta.
                                $("#numero").val("");
                                $("#endereco").val(dados[0].logradouro);
                                $("#bairro").val(dados[0].bairro);
                                $("#cidade").val(dados[0].localidade);
                                $("#cep").val(dados[0].cep);
                                $("#estado").val(dados[0].uf);
                                $('#estado').selectpicker('refresh');
                                
                                $("#div_cep").attr("class", "form-line focused");
                                $("#div_endereco").attr("class", "form-line focused");
                                $("#div_bairro").attr("class", "form-line focused");

                            }
                        }else{
                            alert("CEP não encontrado!");
                            $("#carregar" ).html("<p id='carregar'></p>");
                            $("#mapa").fadeIn("slow");


                            $("#cep").val("");
                            $("#endereco").val("");
                            
                            if(rua != "Unnamed Road"){
                                $("#endereco").val(rua);
                                $("#div_endereco").attr("class", "form-line focused");
                            }

                            $("#bairro").val(bairro);
                            $("#div_bairro").attr("class", "form-line focused");
                        }
                    }).fail(function(){

                            alert("Erro ao encontrar localização.");
                            $("#carregar" ).html("<p id='carregar'></p>");
                            $("#mapa").fadeIn("slow");


                            $("#cep").val("");
                            $("#endereco").val("");
                            
                            if(rua != "Unnamed Road"){
                                $("#endereco").val(rua);
                                $("#div_endereco").attr("class", "form-line focused");
                            }

                            $("#bairro").val(bairro);
                            $("#div_bairro").attr("class", "form-line focused");
                    });


                    marker.setPosition(latLng);
                    map.setCenter(latLng);
                    map.setZoom(17);
                    //setTimeout(function(){$(window).scrollTop(offset.top, offset.left)}, 250);
                    $('#txtLatitude').val(marker.getPosition().lat());
                    $('#txtLongitude').val(marker.getPosition().lng());
                }
            }
        });
    }

$(document).ready(function (){

    initialize();

    function carregarNoMapa(endereco){
        geocoder.geocode({ 'address': endereco + ', Brasil', 'region': 'BR' }, function (results, status) {
            if (status == google.maps.GeocoderStatus.OK) {
                if (results[0]) {
                    var latitude = results[0].geometry.location.lat();
                    var longitude = results[0].geometry.location.lng();

                    $('#endereco').val(results[0].formatted_address);
                    $('#txtLatitude').val(latitude);
                    $('#txtLongitude').val(longitude);

                    var location = new google.maps.LatLng(latitude, longitude);
                    marker.setPosition(location);
                    map.setCenter(location);
                    map.setZoom(18);
                    $("#mapa").show();
                   // $("#mapa").fadeIn("slow");
                }
            }
        })
    }

    google.maps.event.addListener(map, 'click', function (event){
        loadMap(event.latLng);
    });

    // google.maps.event.addListener(map, 'click', function (event){
    //     geocoder.geocode({ 'latLng': event.latLng }, function (results, status) {
    //         if (status == google.maps.GeocoderStatus.OK) {
    //             if (results[0]) {

    //                 var rua = "";
    //                 var bairro = "";
    //                 var cidade = "";
    //                 var uf = "";

    //                 var qtd = results[0].address_components.length;

    //                 if(qtd == 8){

    //                     rua = results[0].address_components[1].long_name ? results[0].address_components[1].long_name : "";
    //                     bairro = results[0].address_components[2].long_name ? results[0].address_components[2].long_name : "";
    //                     cidade = results[0].address_components[3].long_name ? results[0].address_components[3].long_name : "";
                        
    //                 }
    //                 else if(qtd == 7){

    //                     rua = results[0].address_components[1].long_name ? results[0].address_components[1].long_name : "";
    //                     bairro = results[0].address_components[2].long_name ? results[0].address_components[2].long_name : "";
    //                     cidade = results[0].address_components[3].long_name ? results[0].address_components[3].long_name : "";
                        
    //                 }
    //                 else if(qtd == 6){

    //                     rua = results[0].address_components[0].long_name ? results[0].address_components[0].long_name : "";
    //                     bairro = results[0].address_components[1].long_name ? results[0].address_components[1].long_name : "";
    //                     cidade = results[0].address_components[2].long_name ? results[0].address_components[2].long_name : "";

    //                 }
    //                 else if(qtd == 5){

    //                     rua = results[0].address_components[0].long_name ? results[0].address_components[0].long_name : "";
    //                     bairro = results[0].address_components[1].long_name ? results[0].address_components[1].long_name : "";
    //                     cidade = results[0].address_components[1].long_name ? results[0].address_components[1].long_name : "";

    //                 }
    //                 else if(qtd == 4){

    //                     rua = results[0].address_components[0].long_name ? results[0].address_components[0].long_name : "";
    //                     bairro = results[0].address_components[1].long_name ? results[0].address_components[1].long_name : "";
    //                     cidade = results[0].address_components[1].long_name ? results[0].address_components[1].long_name : "";
    //                 }

    //                 var cep = rua + ", " + bairro + ", " + cidade;

    //                 $.ajax({

    //                     type: "POST",
    //                     data: { cep:cep },
    //                     url: "../includes/cep.php",
    //                     dataType: "json",
    //                     beforeSend: function(){
    //                         $("#mapa").fadeOut("slow");
    //                         // $("#endereco").val("");
    //                         // $("#bairro").val("");
    //                         // $("#cidade").val("");
    //                         // $("#estado").val("");
    //                         // $("#cep").val("");
    //                         $("#carregar" ).html("<p id='carregar'><img src='../images/loading.gif'></p>");
    //                     },
    //                     success: function(resultados){
    //                         $("#carregar" ).html("<p id='carregar'></p>");
    //                         var posiMapa = $("#mapa");
    //                         var offset = posiMapa.offset();
    //                         $("#mapa").fadeIn("slow");

    //                         if(resultados == null || resultados.erro != undefined){
    //                             alert("CEP não encontrado.");
    //                         }else{
    //                             //alert(resultados.logradouro);
    //                             //if (resultados.logradouro == results[0].address_components[1].long_name){
    //                             $("#endereco").val(resultados.logradouro);
    //                             $("#bairro").val(resultados.bairro);
    //                             $("#cidade").val(resultados.cidade);
    //                             $("#estado").val(resultados.uf);
    //                             $("#cep").val(resultados.cep); 
                            
    //                         }

    //                     }, 
    //                     error: function(xhr, status, error){
    //                         alert("Erro ao encontrar localização.");
    //                         $("#carregar" ).html("<p id='carregar'></p>");
    //                         $("#mapa").fadeIn("slow");


    //                         $("#cep").val("");
    //                         $("#endereco").val("");
                            
    //                         if(rua != "Unnamed Road")
    //                             $("#endereco").val(rua);

    //                         $("#bairro").val(bairro);


    //                     }
    //                 });

    //                 marker.setPosition(event.latLng);
    //                 map.setCenter(event.latLng);
    //                 map.setZoom(17);
    //                 setTimeout(function(){$(window).scrollTop(offset.top, offset.left)}, 250);
    //                 $('#txtLatitude').val(marker.getPosition().lat());
    //                 $('#txtLongitude').val(marker.getPosition().lng());
    //             }
    //         }
    //     });
    // });

});