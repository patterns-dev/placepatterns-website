function showRPX(element, redirect, action){
  document.getElementById(element).style.display = 'block';
  if (document.getElementById('janrainEngageEmbed') != null) {
    var tokenUrl = window.janrain.settings.tokenUrl;
    var newUrl = tokenUrl;
    if (redirect != '' && redirect != null){
      if (typeof window.janrain == 'object'){
        newUrl = rpxEditUrl(newUrl, 'redirect_to', redirect);
      }
    }
    if (action != '' && action != null){
      if (typeof window.janrain == 'object'){
        newUrl = rpxEditUrl(newUrl, 'action', action);
      }
    }
    window.janrain.settings.tokenUrl = newUrl;
    //Must refresh the widget to read the new token URL.
    window.janrain.engage.signin.widget.refresh();
    //Reset the tokenUrl to original value for next use.
    //window.janrain.settings.tokenUrl = tokenUrl;
    try{
      var wide = document.getElementById('janrainEngageEmbed').children[0].clientWidth;
      var high = document.getElementById('janrainEngageEmbed').children[0].clientHeight;
      wide = wide+2;
      high = high+2;
      document.getElementById('rpx_border').style.width = wide+'px';
      document.getElementById('rpx_border').style.height = high+'px';
    }catch(err){
    }
  }
}

function hideRPX(element){
  document.getElementById(element).style.display = 'none';
}

function hidePassMsg(){
  document.getElementById('reg_passmail').style.display = 'none';
}

function setRPXuser(userlogin){
  document.getElementById('user_login').value=userlogin;
  setTimeout(focusEmail,200);
}

function focusEmail(){
  document.getElementById('user_email').focus();
}

function rpxEditUrl(url, param, value) {
  editDone = false;
  var partsA = url.split('?');
  if (typeof partsA[1] != 'undefined'){
    var partsB = partsA[1].split('&');
    var paramArray = new Array;
    if (typeof partsB[1] != 'undefined'){
      for (i in partsB){
        var partsC = partsB[i].split('=');
        if (partsC[0] == param){
          partsC[1] = value;
          editDone = true;
        }
        paramArray.push(partsC.join('='));
        delete this.partsC;
      }
    }else{
      if (typeof partsB[0] != 'undefined'){
        var partsC = partsB[0].split('=');
        if (partsC[0] == param){
          partsC[1] = value;
          editDone = true;
        }
        paramArray.push(partsC.join('='));
        delete this.partsC;
      }
    }
    if (editDone != true){
      paramArray.push(param+'='+value);
    }
    var output = partsA[0]+'?'+paramArray.join('&');
    return output;
  }
  return url;
}
