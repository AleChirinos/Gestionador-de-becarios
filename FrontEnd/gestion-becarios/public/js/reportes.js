'use strict';

$(document).ready(function(){

    $('.selectedCheckDefault').select2();
    $('.selectedSemesterDefault').select2();

    checkDocumentVisibility(checkLogin);

    cargarReporte();


    $("#groupList, #gestList").change(function(){
        
       var option=$("#groupList").val();
       var semester=$("#gestList").val();
       console.log(option+ ' : ' +semester);

       if (option !=="null"){
        $.ajax({
            url: appRoot+"search/dataSearch",
            type: "get",
            data: {v:option,s:semester},
            success: function(returnedData){
                    if(returnedData.status === 1){
                        var html='';
                        html+='<option value="null">Seleccionar sujeto</option>';
                        $.each( returnedData.allData, function( key, value ) {
                            html+='<option value= ';
                            html+=value.id;
                            html+=' >';
                            html+=value.name;
                            html+='</option>';       
                        });

                        $("#searchOpt").empty().append(html);
                        cargarReporte();
                    }else {
                        cargarReporte();
                        $("#searchOpt").empty();
                        
                    }
                }
            });

       } else {
        $("#searchOpt").empty();
        cargarReporte();
       }

    });



    $("#searchOpt").change(function(){       
        cargarReporte();

    });


});



function cargarReporte(url){
    
    var option=$("#groupList").val();
    var semester=$("#gestList").val();
    var value=$("#searchOpt").val();
    console.log(option + ':' + semester + ':' +value);

    if (value || value!=="null"){
        $.ajax({
            type:'get',
            url: url ? url : appRoot+"reportes/cargarReportes/",
            data: {value:value,semester:semester,option:option},
            success: function(returnedData){
                hideFlashMsg();
                $("#reportesListTable").html(returnedData.reportesListTable);
                console.log(returnedData.allInfo);
            },
            error: function(){
                $("#reportesListTable").html('<h3 style="text-align: center;" >Error al realizar el reporte</h3>');
            }
        });

    } else {
        $("#reportesListTable").html('<h3 style="text-align: center;" >Seleccionar un objeto para el reporte</h3>');
    }

    return false;
}

function resetTrabajoSN(){
    $(".countSN").each(function(i){
        $(this).html(parseInt(i)+1);
    });
}