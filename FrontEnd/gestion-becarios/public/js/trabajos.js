'use strict';

$(document).ready(function(){
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
        
        changeInnerHTML(['trabajoNameErr', 'trabajoHoursErr', 'addCustErrMsg'], "");
        
        var trabajoName = $("#trabajoName").val();
        var trabajoHours = $("#trabajoHours").val();
        var trabajoDescription = $("#trabajoDescription").val();
        
        if(!trabajoName || !trabajoHours){
            !trabajoName ? $("#trabajoNameErr").text("Se requiere llenar el campo") : "";

            !trabajoHours ? $("#trabajoHoursErr").text("Se requiere llenar el campo") : "";
            
            $("#addCustErrMsg").text("Existen uno o varios campos vacíos");
            
            return;
        }
        
        displayFlashMsg("Creando trabajo "+trabajoName+"'", "fa fa-spinner faa-spin animated", '', '');
        
        $.ajax({
            type: "post",
            url: appRoot+"trabajos/add",
            data:{trabajoName:trabajoName, trabajoHours:trabajoHours, trabajoDesc:trabajoDescription},
            
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
                url: appRoot+"search/trabajosearch",
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

        //prefill form with info
        $("#trabajoIdEdit").val(trabajoId);
        $("#trabajoNameEdit").val(trabajoName);
        $("#trabajoDescriptionEdit").val(trabajoDesc);



        $("#editTrabajoFMsg").html("");
        $("#trabajoNameEditErr").html("");



        //launch modal
        $("#editTrabajoModal").modal('show');
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

    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    
    //handles the updating of item's quantity in stock
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
    
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    
    //PREVENT AUTO-SUBMISSION BY THE BARCODE SCANNER
    $("#itemCode").keypress(function(e){
        if(e.which === 13){
            e.preventDefault();
            
            //change to next input by triggering the tab keyboard
            $("#itemName").focus();
        }
    });
    
    
    
    //TO DELETE AN ITEM (The item will be marked as "deleted" instead of removing it totally from the db)
    $("#trabajosListTable").on('click', '.delTrabajo', function(e){
        e.preventDefault();
        
        //get the item id
        var trabajoId = $(this).parents('tr').find('.curTrabajoId').val();
        var trabajoRow = $(this).closest('tr');//to be used in removing the currently deleted row
        
        if(trabajoId){
            var confirm = window.confirm("¿Está seguro de borrar este trabajo? La acción no puede deshacerse");
            
            if(confirm){
                displayFlashMsg('Espere un momento...', spinnerClass, 'black');
                
                $.ajax({
                    url: appRoot+"trabajos/delete",
                    method: "POST",
                    data: {t:trabajoId}
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



function resetTrabajoSN(){
    $(".trabajoSN").each(function(i){
        $(this).html(parseInt(i)+1);
    });
}