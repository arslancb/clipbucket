// JavaScript Document
var win_popup = null;

function openWindow( url, name, params, w, h )
{
      var ah = window.availHeight;
      var aw = window.availWidth;

      var l = (aw - w) / 2;
      var t = (ah - h) / 2;

      params += params == "" ? "" : ",";
      params += "left=0,top=0,height="+ah+",width="+aw;

      //close previous opened popup
      if (win_popup && win_popup.open && !win_popup.closed)
      {
         win_popup.close();
      }
      //---
      win_popup = window.open( "", name, params );
      win_popup.location.href = url;

}