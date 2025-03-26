function opensms(id){
    if(!id){
        return null;
    }
    var cboxes = document.getElementById('open' + id);
    var d = document.getElementById("smssend" + id);
    if (cboxes.checked){
        d.style.display = "flex";
    }else{
        cboxes.form.submit();
        d.style.display = "none";
    }
}
function changesmstype(id, event){
    if(!id){
        return null;
    }


    if(event == 'default'){
        document.getElementById('smstypedefault' + id).style.display = 'block';
        document.getElementById('smstypepattern' + id).style.display = 'none';
    }else{
        document.getElementById('smstypepattern' + id).style.display = 'block';
        document.getElementById('smstypedefault' + id).style.display = 'none';
    }

}

function OpenTagsHook(id, act='def'){
    if(!id){
        return null;
    }

    var divsToHide = document.getElementsByClassName("vo--smsir-tags");
    for(var i = 0; i < divsToHide.length; i++){
        if(divsToHide[i].id != 'vo--smsir-tags' + id){
            divsToHide[i].style.display = "none";
        }
    }

    var d = document.getElementById("vo--smsir-tags" + id);
    if(d.style.display == "block"){
        d.style.display = "none";
    }else{
        d.style.display = "block";
    }
    if(act == 'ptrn'){
        document.getElementById("showGen" + id).style.display = 'block';
    }

}


function PatternGen(id){
    if(!id){
        return null;
    }
    let gen = '';
    let txt1 = document.getElementById('txt' + id + 'a').value;
    let txt2 = document.getElementById('txt' + id + 'b').value;
    let txt3 = document.getElementById('txt' + id + 'c').value;
    let txt4 = document.getElementById('txt' + id + 'd').value;
    let txt5 = document.getElementById('txt' + id + 'e').value;
    let vlu1 = document.getElementById('vlu' + id + 'a').value;
    let vlu2 = document.getElementById('vlu' + id + 'b').value;
    let vlu3 = document.getElementById('vlu' + id + 'c').value;
    let vlu4 = document.getElementById('vlu' + id + 'd').value;
    let vlu5 = document.getElementById('vlu' + id + 'e').value;

    gen = '{';
    if(txt1 && vlu1){
        gen += '"' + txt1 + '": "' + vlu1 + '",';
    }
    if(txt2 && vlu2){
        gen += '"' + txt2 + '": "' + vlu2 + '",';
    }
    if(txt3 && vlu3){
        gen += '"' + txt3 + '": "' + vlu3 + '",';
    }
    if(txt4 && vlu4){
        gen += '"' + txt4 + '": "' + vlu4 + '",';
    }
    if(txt5 && vlu5){
        gen += '"' + txt5 + '": "' + vlu5 + '",';
    }
    gen += '}';
    gen = gen.replace(",}", "}");
    gen = gen.replaceAll("#", "");


    document.getElementById('showCodeGen' + id).value = gen;

}



function ChangeVerifyStatus(act='default'){
    console.log(act);
    if(act == 'default'){
        document.getElementById('verify_typesend_default').style.display = 'block';
        document.getElementById('verify_typesend_pattern').style.display = 'none';
    }else{
        document.getElementById('verify_typesend_pattern').style.display = 'block';
        document.getElementById('verify_typesend_default').style.display = 'none';
    }

}


function SendBuilkSms(event){
    if(event == 'default'){
        document.getElementById('defualtsend').style.display = 'block';
        document.getElementById('patternsend').style.display = 'none';
    }else{
        document.getElementById('patternsend').style.display = 'block';
        document.getElementById('defualtsend').style.display = 'none';
    }

}

function bulkSendForUsers(event){
    if(event == 'users') {
        document.getElementById('SendForUser').style.display = 'block';
        document.getElementById('SendForService').style.display = 'none';
        document.getElementById('SendForDomains').style.display = 'none';
    }else if(event == 'service'){
        document.getElementById('SendForUser').style.display = 'none';
        document.getElementById('SendForService').style.display = 'block';
        document.getElementById('SendForDomains').style.display = 'none';
    }else{
        document.getElementById('SendForUser').style.display = 'none';
        document.getElementById('SendForService').style.display = 'none';
        document.getElementById('SendForDomains').style.display = 'block';
    }

}