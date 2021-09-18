
function watermark(imgUrl,imgDom){

    //建立canvas畫布
    var wtCanvas = document.createElement("canvas");
    var context = wtCanvas.getContext("2d");
    
    //宣告圖片大小(因為圖片讀取為異步讀取)
    var imgSize = {w:0,h:0}
    var mainImg = new Image(); //建立新圖片(主圖用)
    mainImg.src = imgUrl;      //指定連結
    //讀取圖片並繪製到canvas畫布
    mainImg.onload = function() {
   
      //紀錄圖片大小
      imgSize.w = mainImg.width;
      imgSize.h = mainImg.height;
      //設定畫布大小與圖片一致
      wtCanvas.width = mainImg.width;
      wtCanvas.height = mainImg.height;
      //將圖片繪製至canvas畫布
      context.drawImage(mainImg, 0, 0,mainImg.width,mainImg.height);

      var logoImg = new Image();  //建立新圖片(浮水印用)
      logoImg.src = '/js/logo-watermark.png';   //指定浮水印連結
      //讀取浮水印 並計算 此浮水印尺寸需填滿原圖 之 長寬數量
      logoImg.onload = function() {
        //計算原圖長寬要放幾張logo
        var _logoW = logoImg.width;
        var _logoH = logoImg.height;
        var horizontal = Math.ceil(imgSize.w/_logoW);
        var vertical = Math.ceil(imgSize.h/_logoH);
        for(var h = 0; h < horizontal;h++){
            for(var v = 0; v < vertical;v++){
                //設定半透明
                context.globalAlpha = 0.5;
                context.drawImage(
                    logoImg,logoImg.width*h,logoImg.height*v,logoImg.width,logoImg.height);
            }
        }
        //轉出base64圖片格式
        var dataURL = wtCanvas.toDataURL();
        //將產生的base64圖片格式 替換指定圖片對象的src
        imgDom.src = dataURL;
      };
    };
};



