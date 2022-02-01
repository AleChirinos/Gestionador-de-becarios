'use strict';

$(document).ready(function(){
    $('.selectedBecarioDefault').select2()
    checkDocumentVisibility(checkLogin);//check document visibility in order to confirm user's log in status

    //load all items once the page is ready
    cargarTrabajos();

    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    /**
     * Toggle the form to add a new item
     */
    $("#createTrabajo").click(function(){
        $("#trabajosListDiv").toggleClass("col-sm-8", "col-sm-12");
        $("#createNewTrabajoDiv").toggleClass('hidden');
        $("#trabajoName").focus();
    });

    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


    $(".cancelAddTrabajo").click(function(){
        //reset and hide the form
        document.getElementById("addNewTrabajoForm").reset();//reset the form
        $("#createNewTrabajoDiv").addClass('hidden');//hide the form
        $("#trabajosListDiv").attr('class', "col-sm-12");//make the table span the whole div
    });

    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    //handles the submission of adding new item
    $("#addNewTrabajo").click(function(e){
        e.preventDefault();

        changeInnerHTML(['trabajoNameErr', 'trabajoHoursErr', 'addCustErrMsg','careerErr','semesterErr'], "");

        var trabajoName = $("#trabajoName").val();
        var trabajoHours = $("#trabajoHours").val();
        var trabajoDescription = $("#trabajoDescription").val();
        var career = $("#career").val();
        var semester = $("#semester").val();
        if(!trabajoName || !trabajoHours|| !career|| !semester){
            !trabajoName ? $("#trabajoNameErr").text("Se requiere llenar el campo") : "";

            !trabajoHours ? $("#trabajoHoursErr").text("Se requiere llenar el campo") : "";
            !career ? changeInnerHTML('careerErr', "required") : "";
            !semester ? changeInnerHTML('semesterErr', "required") : "";
            $("#addCustErrMsg").text("Existen uno o varios campos vacíos");

            return;
        }

        displayFlashMsg("Creando trabajo "+trabajoName+"'", "fa fa-spinner faa-spin animated", '', '');

        $.ajax({
            type: "post",
            url: appRoot+"trabajos/add",
            data:{trabajoName:trabajoName, trabajoHours:trabajoHours, trabajoDesc:trabajoDescription, career:career, semester:semester},

            success: function(returnedData){
                if(returnedData.status === 1){
                    changeFlashMsgContent(returnedData.msg, "text-success", '', 1500);
                    document.getElementById("addNewTrabajoForm").reset();

                    //refresh the items list table
                    cargarTrabajos();

                    //return focus to item code input to allow adding item with barcode scanner
                    $("#trabajoName").focus();
                }

                else{
                    hideFlashMsg();

                    //display all errors
                    $("#trabajoNameErr").text(returnedData.trabajoName);
                    $("#trabajoHoursErr").text(returnedData.trabajoHours);
                    $("#addCustErrMsg").text(returnedData.msg);
                    $("#careerErr").text(returnedData.career);
                    $("#semesterErr").text(returnedData.semester);
                }
            },

            error: function(){
                if(!navigator.onLine){
                    changeFlashMsgContent("El sistema está fuera de línea. Verificar conexión a internet e intentar nuevamente", "", "red", "");
                }

                else{
                    changeFlashMsgContent("No se puede realizar la acción en este momento. Por favor, intentar nuevamente más tarde", "", "red", "");
                }
            }
        });
    });


    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    //reload items list table when events occur
    $("#trabajosListPerPage, #trabajosListSortBy").change(function(){
        displayFlashMsg("Espere un momento...", spinnerClass, "", "");
        cargarTrabajos();
    });

    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    $("#trabajoSearch").keyup(function(){
        var value = $(this).val();


        if(value){
            $.ajax({
                url: appRoot+"search/trabajoSearch",
                type: "get",
                data: {v:value},
                success: function(returnedData){
                    $("#trabajosListTable").html(returnedData.trabajosListTable);
                }
            });
        }

        else{
            //reload the table if all text in search box has been cleared
            displayFlashMsg("Cargando página...", spinnerClass, "", "");
            cargarTrabajos();
        }
    });

    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    //triggers when an item's "edit" icon is clicked
    $("#trabajosListTable").on('click', ".editTrabajo", function(e){
        e.preventDefault();

        //get item info
        var trabajoId = $(this).attr('id').split("-")[1];
        var trabajoName = $("#trabajoName-"+trabajoId).html();
        var trabajoDesc = $("#trabajoDesc-"+trabajoId).attr('title');
        var career = $(this).siblings(".career").html();
        //prefill form with info
        $("#trabajoIdEdit").val(trabajoId);
        $("#trabajoNameEdit").val(trabajoName);
        $("#trabajoDescriptionEdit").val(trabajoDesc);
        $("#editTrabajoFMsg").html("");
        $("#trabajoNameEditErr").html("");
        $("#careerEdit").val(career);


        //launch modal
        $("#editTrabajoModal").modal('show');
    });

    $("#trabajosListTable").on('click', ".assignBecarios", function(){


        //get item info
        var trabajoId = $(this).attr('id').split("-")[1];
        var trabajoName = $("#trabajoName-"+trabajoId).html();
        var trabajoHours = $("#workhours-"+trabajoId).html();
        var trabajoSem= $("#trabajoSem-"+trabajoId).html();
       


        //prefill form with info
        $("#trabajoIdBec").val(trabajoId);
        $("#trabajoSem").val(trabajoSem);
        $("#trabajoNameBec").val(trabajoName);
        $("#becarioAssignHours").val(trabajoHours);
        $("#becarioDisHours").val("");


        $("#becTrabajoFMsg").html("");
        $("#trabajoNameBecErr").html("");
        $("#becarioAssignHoursErr").html("");
        $("#becarioDisHoursErr").html("");
        $("#selectedBecarioDefaultErr").html("");

        
            $.ajax({
                url: appRoot+"search/becarioSemSearch",
                type: "get",
                data: {v:trabajoSem},
                success: function(returnedData){
                        if(returnedData.status === 1){
                            console.log(returnedData.semester);
                            
                            var html='';
                            html+='<option selected>Selecciona a tu becario:</option>';
                            $.each( returnedData.allData, function( key, value ) {
                                html+='<option value= ';
                                html+=value.code;
                                html+=' >';
                                html+=value.name;
                                html+='</option>';       
                            });
    
                            $("#selectedBecarioDefault").empty().append(html);
                           
                        }else {
                            var html='<option selected>Selecciona a tu becario:</option>';
                            $("#selectedBecarioDefault").empty().append(html);  
                        }
                    }
                });
    

        $("#selectedBecarioDefault").val("Selecciona a tu becario:");
        $("#addBecarioTrabajoModal").modal('show');

    });

    $("#assignBecarioSubmit").click(function(){

        var trabajoName = $("#trabajoNameBec").val();
        var trabajoId = $("#trabajoIdBec").val();
        var becarioCode = $("#selectedBecarioDefault").val();
        var becarioName=$("#trabajoBecName").val();
        var trabajoHours=$("#becarioAssignHours").val();
        var becarioHours=$("#becarioDisHours").val();
        var becarioId=$("#becId").val();

        console.log('Trabajo: Nombre '+trabajoName+' Id '+trabajoId+ ' hours '+trabajoHours);
        console.log('Becario: Nombre '+becarioName+' Codigo '+becarioCode+ ' becarioHours '+becarioHours+' id '+becarioId);




        if(!becarioHours || (!becarioCode || becarioCode==='Selecciona a tu becario:') ){
            !becarioHours || becarioHours==0 ? $("#becarioDisHoursErr").html("Becario no escogido") : "";
            !becarioCode || becarioCode==='Selecciona a tu becario:'? $("#selectedBecarioDefaultErr").html("Se requiere escoger un becario") : "";
            return;
        }

        $("#becTrabajoFMsg").css('color', 'black').html("<i class='"+spinnerClass+"'></i> Realizando la asignación...");

        $.ajax({
            method: "post",
            url: appRoot+"trabajos/assignBecario",
            data: {becarioName:becarioName,becarioCode:becarioCode, trabajoName:trabajoName ,_tId:trabajoId, _bId:becarioId,trabajoHours:trabajoHours, becHours:becarioHours}
        }).done(function(returnedData){

            if(returnedData.status === 1){
                $("#becTrabajoFMsg").css('color', 'green').html("Asignación del becario exitosa");

                setTimeout(function(){
                    $("#addBecarioTrabajoModal").modal('hide');
                }, 1000);

                cargarTrabajos();
            }

            else{
                $("#becTrabajoFMsg").css('color', 'red').html("Existen uno o más campos vacíos o llenados de manera incorrecta");
                $("#becarioDisHoursErr").html(returnedData.becarioHours);
                $("#selectedBecarioDefaultErr").html(returnedData.becarioName);
            }
        }).fail(function(){
            $("#becTrabajoFMsg").css('color', 'red').html("No se puede realizar la acción en este momento. Por favor, verificar conexión a internet e intentar nuevamente más tarde");
        });

        $("#trabajoNameBecErr").html("");
        $("#becarioAssignHoursErr").html("");
        $("#becarioDisHoursErr").html("");
        $("#selectedBecarioDefaultErr").html("");


    });






    $("#editTrabajoSubmit").click(function(){
        var trabajoName = $("#trabajoNameEdit").val();
        var trabajoId = $("#trabajoIdEdit").val();
        var trabajoDesc = $("#trabajoDescriptionEdit").val();

        if(!trabajoName || !trabajoId){
            !trabajoName ? $("#trabajoNameEditErr").html("El campo de nombre no debe estar vacío") : "";
            !trabajoId ? $("#editTrabajoFMsg").html("Trabajo desconocido") : "";
            return;
        }

        $("#editTrabajoFMsg").css('color', 'black').html("<i class='"+spinnerClass+"'></i> Realizando la edición...");

        $.ajax({
            method: "POST",
            url: appRoot+"trabajos/edit",
            data: {trabajoName:trabajoName, _tId:trabajoId, trabajoDesc:trabajoDesc}
        }).done(function(returnedData){
            if(returnedData.status === 1){
                $("#editTrabajoFMsg").css('color', 'green').html("Información del trabajo actualizada");

                setTimeout(function(){
                    $("#editTrabajoModal").modal('hide');
                }, 1000);

                cargarTrabajos();
            }

            else{
                $("#editTrabajoFMsg").css('color', 'red').html("Existen uno o más campos vacíos o llenados de manera incorrecta");

                $("#trabajoNameEditErr").html(returnedData.trabajoName);

            }
        }).fail(function(){
            $("#editTrabajoFMsg").css('color', 'red').html("No se puede realizar la acción en este momento. Por favor, verificar conexión a internet e intentar nuevamente más tarde");
        });
    });


    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    //trigers the modal to update stock
    $("#trabajosListTable").on('click', '.updateTrabajoHours', function(){
        //get item info and fill the form with them
        var trabajoId = $(this).attr('id').split("-")[1];
        var trabajoName = $("#trabajoName-"+trabajoId).html();
        var workHours = $("#workhours-"+trabajoId).html();

        $("#thUpdateTrabajoId").val(trabajoId);
        $("#thUpdateTrabajoName").val(trabajoName);
        $("#thUpdateTrabajoHours").val(workHours);

        $("#updateTrabajoHoursModal").modal('show');
    });


    $("#trabajosListTable").on('click', '.checkTrabajo', function(){
        //get item info and fill the form with them
        var trabajoId = $(this).attr('id').split("-")[1];


        $("#checkTrabajoModal").modal('show');

        $.ajax({
            url: appRoot+"search/asignadoSearch",
            type: "get",
            data: {v:trabajoId},
            success: function(returnedData){
                if(returnedData.status === 1){

                    var html='';
                    html+='<form role="form">'
                    $.each( returnedData.allAsignados, function( key, value ) {
                        html+='<div class="row" >';


                        html+='<div class="col-sm-4 form-group-sm"><label for="checkNameCheck">Nombre del becario</label><input type="text" id="checkNameCheck" name="checkNameCheck" readonly value="';
                        html+=value["becarioName"];
                        html+='" placeholder="Nombre del becario" autofocus class="form-control checkField"><span class="help-block errMsg" id="checkNameCheckErr"></span></div>';

                        html+='<input type="hidden" id="checkCodeCheck" name="checkCodeCheck" readonnly value=';
                        html+=value["becarioCode"];
                        html+='>';

                        html+='<div class="col-sm-4 form-group-sm"><label for="checkHourCheck">Horas del trabajo</label><input type="number" id="checkHourCheck" name="checkHourCheck" value=';
                        html+=value["hours"];
                        html+=' class="form-control checkField" min="0"><span class="help-block errMsg" id="checkHourCheckErr"></span></div>';

                        html+='</div>';
                    });
                    html+='<input type="hidden" id="trabajoIdCheck">';
                    html+='</div></form>';

                    $('#checkTrabajoModal').find('.modal-body').html(html);
                    $('#trabajoIdCheck').val(trabajoId);
                    $("#checkTrabajoSubmit").prop('disabled',false);
                }else {
                    $("#checkTrabajoSubmit").prop('disabled',true);
                    $('#checkTrabajoModal').find('.modal-body').html('<h4 class="text-center">No existen becarios asignados</h4>');
                }
            }

        });

    });


    $("#checkTrabajoSubmit").click(function(){

        var trabajoId = $("#trabajoIdCheck").val();
        var nameArray=[];
        var hoursArray=[];
        var codeArray=[];
        $('input[name="checkNameCheck"]').each(function (i, item)
        {
            nameArray.push(item.value);
        });
        $('input[name="checkHourCheck"]').each(function (i, item)
        {
            hoursArray.push(item.value);
        });
        $('input[name="checkCodeCheck"]').each(function (i, item)
        {
            codeArray.push(item.value);
        });
        var len=hoursArray.length;

        var jsonCAr=JSON.stringify(codeArray, null, 2);
        var jsonNAr = JSON.stringify(nameArray, null, 2);
        var jsonHAr= JSON.stringify(hoursArray, null, 2);

        console.log(codeArray);



        $("#checkTrabajoFMsg").html("<i class='"+spinnerClass+"'></i> Completando trabajo...");

        $.ajax({
            method: "POST",
            url: appRoot+"trabajos/checkTrabajos",
            data: {_tId:trabajoId, becarioName:jsonNAr, hoursAssign:jsonHAr,becarioCode:jsonCAr,length:len}
        }).done(function(returnedData){
            if(returnedData.status === 1){

                $("#checkTrabajoFMsg").css('color', 'green').html(returnedData.msg);

                setTimeout(function(){
                    $("#checkTrabajoModal").modal('hide');
                    $("#checkTrabajoFMsg").html("");
                }, 1000);

                cargarTrabajos();
            }

            else{
                $("#checkTrabajoFMsg").html(returnedData.msg);

            }
        }).fail(function(){
            $("#checkTrabajoFMsg").html("No se puede realizar la acción en este momento. Por favor, verificar conexión a internet e intentar nuevamente más tarde");
        });




    });

    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


    $("#thUpdateSubmit").click(function(){
        var thUpdateTrabajoHours = $("#thUpdateTrabajoHours").val();
        var trabajoId = $("#thUpdateTrabajoId").val();

        if(!thUpdateTrabajoHours || !trabajoId){
            !thUpdateTrabajoHours ? $("#thUpdateTrabajoHoursErr").html("El campo de horas faltantes no debe estar vacío") : "";
            !trabajoId ? $("#thUpdateTrabajoIdErr").html("El id del trabajo no debe estar vacío") : "";
            return;
        }

        $("#thUpdateFMsg").html("<i class='"+spinnerClass+"'></i> Modificando horas de trabajo faltantes...");

        $.ajax({
            method: "POST",
            url: appRoot+"trabajos/updateTrabajoHours",
            data: {_tId:trabajoId, thUpdateTrabajoHours:thUpdateTrabajoHours}
        }).done(function(returnedData){
            if(returnedData.status === 1){
                $("#thUpdateFMsg").css('color', 'green').html(returnedData.msg);

                setTimeout(function(){
                    $("#updateTrabajoHoursModal").modal('hide');//hide modal
                    $("#thUpdateFMsg").html("");
                }, 1000);

                cargarTrabajos();

            }

            else{
                $("#thUpdateFMsg").html(returnedData.msg);
                $("#thUpdateTrabajoHoursErr").html(returnedData.thUpdateTrabajoHours);
            }
        }).fail(function(){
            $("#thUpdateFMsg").html("No se puede realizar la acción en este momento. Por favor, verificar conexión a internet e intentar nuevamente más tarde");
        });
    });




    //TO DELETE AN ITEM (The item will be marked as "deleted" instead of removing it totally from the db)
    $("#trabajosListTable").on('click', '.delTrabajo', function(e){
        e.preventDefault();

        //get the item id
        var trabajoId = $(this).parents('tr').find('.curTrabajoId').val();
        var trabajoRow = $(this).closest('tr');//to be used in removing the currently deleted row
        var trabajoName = $("#trabajoName-"+trabajoId).html();
        var trabajoHours = $("#workhours-"+trabajoId).html();

        if(trabajoId){
            var confirm = window.confirm("¿Está seguro de borrar este trabajo? La acción no puede deshacerse");

            if(confirm){
                displayFlashMsg('Espere un momento...', spinnerClass, 'black');

                $.ajax({
                    url: appRoot+"trabajos/delete",
                    method: "POST",
                    data: {t:trabajoId,tn:trabajoName,th:trabajoHours}
                }).done(function(rd){
                    if(rd.status === 1){
                        //remove item from list, update items' SN, display success msg
                        $(trabajoRow).remove();

                        //update the SN
                        resetTrabajoSN();

                        cargarTrabajos();

                        //display success message
                        changeFlashMsgContent('Trabajo eliminado del sistema', '', 'green', 1000);
                    }

                    else{

                    }
                }).fail(function(){
                    console.log('Hubo una falla en el proceso');
                });
            }
        }
    });

    $("#clickToGen").click(function(e){
        e.preventDefault();


        var strWindowFeatures = "width=1000,height=500,scrollbars=yes,resizable=yes";

        window.open(appRoot+"trabajos/report/", 'Print', strWindowFeatures);
    });


});



function cargarTrabajos(url){
    var orderBy = $("#trabajosListSortBy").val().split("-")[0];
    var orderFormat = $("#trabajosListSortBy").val().split("-")[1];
    var limit = $("#trabajosListPerPage").val();

    


    $.ajax({
        type:'get',
        url: url ? url : appRoot+"trabajos/cargarTrabajos/",
        data: {orderBy:orderBy, orderFormat:orderFormat, limit:limit},

        success: function(returnedData){
            hideFlashMsg();
            $("#trabajosListTable").html(returnedData.trabajosListTable);
        },

        error: function(){

        }
    });

    return false;
}




/**
 * "vittrhist" = "View item's transaction history"
 * @param {type} itemId
 * @returns {Boolean}
 */
function vittrhist(itemId){
    if(itemId){

    }

    return false;
}

function selectedBecario(selectedNode){
    if(selectedNode){
        var itemCode = selectedNode.value;

        $.ajax({
            url: appRoot+"becarios/getcodenameandhours",
            type: "get",
            data: {_bC:itemCode},
            success: function(returnedData){
                if(returnedData.status === 1){

                    $("#becarioDisHours").val(returnedData.missinghours);
                    $("#trabajoBecName").val(returnedData.name);
                    $("#becId").val(returnedData.becarioId);


                }else{
                    $("#becarioDisHours").val("0");
                    $("#trabajoBecName").val("");
                    $("#becId").val("");
                }

            }
        });
    }
}


function resetTrabajoSN(){
    $(".trabajoSN").each(function(i){
        $(this).html(parseInt(i)+1);
    });
}