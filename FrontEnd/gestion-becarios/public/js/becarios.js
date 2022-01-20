'use strict';

$(document).ready(function(){
    checkDocumentVisibility(checkLogin);//check document visibility in order to confirm user's log in status

    //load all items once the page is ready
    cargarBecarios();



    //WHEN USE BARCODE SCANNER IS CLICKED
    $("#useBarcodeScanner").click(function(e){
        e.preventDefault();

        $("#becarioCode").focus();
    });


    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    /**
     * Toggle the form to add a new item
     */
    $("#createBecario").click(function(){
        $("#becariosListDiv").toggleClass("col-sm-8", "col-sm-12");
        $("#createNewBecarioDiv").toggleClass('hidden');
        $("#becarioName").focus();
    });


    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


    $(".cancelAddBecario").click(function(){
        //reset and hide the form
        document.getElementById("addNewBecarioForm").reset();//reset the form
        $("#createNewBecarioDiv").addClass('hidden');//hide the form
        $("#becariosListDiv").attr('class', "col-sm-12");//make the table span the whole div
    });


    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    //execute when 'auto-generate' checkbox is clicked while trying to add a new item
    $("#gen4me").click(function(){
        //if checked, generate a unique item code for user. Else, clear field
        if($("#gen4me").prop("checked")){
            var codeExist = false;

            do{
                //generate random string, reduce the length to 10 and convert to uppercase
                var rand = Math.random().toString(36).slice(2).substring(0, 10).toUpperCase();
                $("#becarioCode").val(rand);//paste the code in input
                $("#becarioCodeErr").text('');//remove the error message being displayed (if any)

                //check whether code exist for another item
                $.ajax({
                    type: 'get',
                    url: appRoot+"becarios/gettablecol/id/code/"+rand,
                    success: function(returnedData){
                        codeExist = returnedData.status;//returnedData.status could be either 1 or 0
                    }
                });
            }

            while(codeExist);

        }

        else{
            $("#becarioCode").val("");
        }
    });

    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    //handles the submission of adding new item
    $("#addNewBecario").click(function(e){
        e.preventDefault();

        changeInnerHTML(['becarioNameErr', 'becarioCodeErr', 'addCustErrMsg'], "");

        var becarioName = $("#becarioName").val();
        var becarioCode = $("#becarioCode").val();

        if(!becarioName || !becarioCode){
            !becarioName ? $("#becarioNameErr").text("Se requiere llenar el campo") : "";

            !becarioCode ? $("#becarioCodeErr").text("Se requiere llenar el campo") : "";

            $("#addCustErrMsg").text("Existen uno o varios campos vacíos");

            return;
        }

        displayFlashMsg("Inscribiendo becario "+becarioName+"'", "fa fa-spinner faa-spin animated", '', '');

        $.ajax({
            type: "post",
            url: appRoot+"becarios/add",
            data:{becarioName:becarioName, becarioCode:becarioCode},

            success: function(returnedData){
                if(returnedData.status === 1){
                    changeFlashMsgContent(returnedData.msg, "text-success", '', 1500);
                    document.getElementById("addNewBecarioForm").reset();

                    //refresh the items list table
                    cargarBecarios();

                    //return focus to item code input to allow adding item with barcode scanner
                    $("#becarioCode").focus();
                }

                else{
                    hideFlashMsg();

                    //display all errors
                    $("#becarioNameErr").text(returnedData.becarioName);
                    $("#becarioCodeErr").text(returnedData.becarioCode);
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
    $("#becariosListPerPage, #becariosListSortBy").change(function(){
        displayFlashMsg("Espere un momento...", spinnerClass, "", "");
        cargarBecarios();
    });

    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    $("#becarioSearch").keyup(function(){
        var value = $(this).val();

        if(value){
            $.ajax({
                url: appRoot+"search/becariosearch",
                type: "get",
                data: {v:value},
                success: function(returnedData){
                    $("#becariosListTable").html(returnedData.becariosListTable);
                }
            });
        }

        else{
            //reload the table if all text in search box has been cleared
            displayFlashMsg("Cargando página...", spinnerClass, "", "");
            cargarBecarios();
        }
    });

    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    //triggers when an item's "edit" icon is clicked
    $("#becariosListTable").on('click', ".editBecario", function(e){
        e.preventDefault();

        //get item info
        var becarioId = $(this).attr('id').split("-")[1];
        var becarioName = $("#becarioName-"+becarioId).html();
        var becarioCode = $("#becarioCode-"+becarioId).html();

        //prefill form with info
        $("#becarioIdEdit").val(becarioId);
        $("#becarioNameEdit").val(becarioName);
        $("#becarioCodeEdit").val(becarioCode);



        $("#editBecarioFMsg").html("");
        $("#becarioNameEditErr").html("");
        $("#becarioCodeEditErr").html("");



        //launch modal
        $("#editBecarioModal").modal('show');
    });



    $("#editBecarioSubmit").click(function(){
        var becarioName = $("#becarioNameEdit").val();
        var becarioId = $("#becarioIdEdit").val();
        var becarioCode = $("#becarioCodeEdit").val();

        if(!becarioName || !becarioCode || !becarioId){
            !becarioName ? $("#becarioNameEditErr").html("El campo de nombre no debe estar vacío") : "";
            !becarioCode ? $("#becarioCodeEditErr").html("El campo de código no debe estar vacío") : "";
            !becarioId ? $("#becarioItemFMsg").html("Becario desconocido") : "";
            return;
        }

        $("#editBecarioFMsg").css('color', 'black').html("<i class='"+spinnerClass+"'></i> Realizando la edición...");

        $.ajax({
            method: "POST",
            url: appRoot+"becarios/edit",
            data: {becarioName:becarioName, _bId:becarioId, becarioCode:becarioCode}
        }).done(function(returnedData){
            if(returnedData.status === 1){
                $("#editBecarioFMsg").css('color', 'green').html("Información de becario actualizada");

                setTimeout(function(){
                    $("#editBecarioModal").modal('hide');
                }, 1000);

                cargarBecarios();
            }

            else{
                $("#editBecarioFMsg").css('color', 'red').html("Existen uno o más campos vacíos o llenados de manera incorrecta");

                $("#becarioNameEditErr").html(returnedData.becarioName);
                $("#becarioCodeEditErr").html(returnedData.becarioCode);

            }
        }).fail(function(){
            $("#editBecarioFMsg").css('color', 'red').html("No se puede realizar la acción en este momento. Por favor, verificar conexión a internet e intentar nuevamente más tarde");
        });
    });


    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    //trigers the modal to update stock
    $("#becariosListTable").on('click', '.updateMissingHours', function(){
        //get item info and fill the form with them
        var becarioId = $(this).attr('id').split("-")[1];
        var becarioName = $("#becarioName-"+becarioId).html();
        var becarioMissingHours = $("#missinghours-"+becarioId).html();
        var becarioCode = $("#becarioCode-"+becarioId).html();

        $("#mhUpdateBecarioId").val(becarioId);
        $("#mhUpdateBecarioName").val(becarioName);
        $("#mhUpdateBecarioCode").val(becarioCode);
        $("#mhUpdateMissingHours").val(becarioMissingHours);

        $("#updateMissingHoursModal").modal('show');
    });

    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    //handles the updating of item's quantity in stock
    $("#mhUpdateSubmit").click(function(){
        var mhUpdateMissingHours = $("#mhUpdateMissingHours").val();
        var becarioId = $("#mhUpdateBecarioId").val();

        if(!mhUpdateMissingHours || !becarioId){
            !mhUpdateMissingHours ? $("#mhUpdateMissingHoursErr").html("El campo de horas faltantes no debe estar vacío") : "";
            !becarioId ? $("#mhUpdateBecarioIdErr").html("El id del becario no debe estar vacío") : "";
            return;
        }

        $("#mhUpdateFMsg").html("<i class='"+spinnerClass+"'></i> Modificando horas de trabajo faltantes...");

        $.ajax({
            method: "POST",
            url: appRoot+"becarios/updateMissingHours",
            data: {_bId:becarioId, mhUpdateMissingHours:mhUpdateMissingHours}
        }).done(function(returnedData){
            if(returnedData.status === 1){
                $("#mhUpdateFMsg").css('color', 'green').html(returnedData.msg);

                setTimeout(function(){
                    $("#updateMissingHoursModal").modal('hide');//hide modal
                    $("#mhUpdateFMsg").html("");
                }, 1000);

                cargarBecarios();

            }

            else{
                $("#mhUpdateFMsg").html(returnedData.msg);
                $("#mhUpdateDescriptionErr").html(returnedData.mhUpdateMissingHours);
            }
        }).fail(function(){
            $("#mhUpdateFMsg").html("No se puede realizar la acción en este momento. Por favor, verificar conexión a internet e intentar nuevamente más tarde");
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
    $("#becariosListTable").on('click', '.delBecario', function(e){
        e.preventDefault();

        //get the item id
        var becarioId = $(this).parents('tr').find('.curBecarioId').val();
        var becarioRow = $(this).closest('tr');//to be used in removing the currently deleted row
        var becarioName = $("#becarioName-"+becarioId).html();

        if(becarioId){
            var confirm = window.confirm("¿Está seguro de borrar este becario? La acción no puede deshacerse");

            if(confirm){
                displayFlashMsg('Espere un momento...', spinnerClass, 'black');

                $.ajax({
                    url: appRoot+"becarios/delete",
                    method: "POST",
                    data: {b:becarioId,bn:becarioName}
                }).done(function(rd){
                    if(rd.status === 1){
                        //remove item from list, update items' SN, display success msg
                        $(becarioRow).remove();

                        //update the SN
                        resetBecarioSN();

                        cargarBecarios();

                        //display success message
                        changeFlashMsgContent('Becario eliminado del sistema', '', 'green', 1000);
                    }

                    else{

                    }
                }).fail(function(){
                    console.log('Hubo un fallo en el procedimiento');
                });
            }
        }
    });

    $("#clickToGen").click(function(e){
        e.preventDefault();


        var strWindowFeatures = "width=1000,height=500,scrollbars=yes,resizable=yes";

        window.open(appRoot+"becarios/report/", 'Print', strWindowFeatures);
    });


});



function cargarBecarios(url){
    var orderBy = $("#becariosListSortBy").val().split("-")[0];
    var orderFormat = $("#becariosListSortBy").val().split("-")[1];
    var limit = $("#becariosListPerPage").val();


    $.ajax({
        type:'get',
        url: url ? url : appRoot+"becarios/cargarBecarios/",
        data: {orderBy:orderBy, orderFormat:orderFormat, limit:limit},

        success: function(returnedData){
            hideFlashMsg();
            $("#becariosListTable").html(returnedData.becariosListTable);
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



function resetBecarioSN(){
    $(".becarioSN").each(function(i){
        $(this).html(parseInt(i)+1);
    });
}