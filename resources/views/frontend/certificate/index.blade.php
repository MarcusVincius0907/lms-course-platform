<input type="hidden" id="urlCertificate" value="{{assetC($cert_template->template_path)}}">
<input type="hidden" id="urlFont" value="{{assetC('frontend/certificate/TTNorms-Regular.otf')}}">
<input type="hidden" id="urlCertificateEdited" value="{{route('certificateEdited')}}">
<input type="hidden" id="objData" value="{{json_encode($obj)}}">
<form action="" id="formCerfi" enctype="multipart/form-data">
  @csrf
  <input type="hidden" value="{{$cert->course_id}}" name="course_id">
</form>
<iframe id="pdf" src="" style="position:fixed; top:0; left:0; bottom:0; right:0; width:100%; height:100%; border:none; margin:0; padding:0; overflow:hidden; z-index:999999;"></iframe>
<script src="https://unpkg.com/pdf-lib"></script>
<script src="https://unpkg.com/@pdf-lib/fontkit@0.0.4"></script>
<script>
  const url = document.querySelector('#urlCertificate') ? document.querySelector('#urlCertificate').value : null ;
  const urlFinal = document.querySelector('#urlCertificateEdited') ? document.querySelector('#urlCertificateEdited').value : null ;
  const urlFont = document.querySelector("#urlFont") ? document.querySelector("#urlFont").value : null;
  let objData = document.querySelector('#objData') ? JSON.parse(document.querySelector('#objData').value) : null;
  const _token = document.querySelector("input[name='_token']") ? document.querySelector("input[name='_token']").value : null;
  const fileForm = document.querySelector('#fileForm');

  

  const main = async () =>{

    const generatePDF = async () => {

      const {PDFDocument, rgb} = PDFLib;

      /* pdf init config */

      const existingPdfBytes = await fetch(url).then((res) =>  
        res.arrayBuffer()
      );

      const exFont = await fetch(urlFont).then((res) =>  
        res.arrayBuffer()
      );

      const pdfDoc = await PDFDocument.load(existingPdfBytes);

      const pages = pdfDoc.getPages();
      const firstPg = pages[0];
      const secondPg = pages[1];


      pdfDoc.registerFontkit(fontkit);

      const Font = await pdfDoc.embedFont(exFont);

      /* pdf init config */

      /* helper */

      const getStringWidth = (string, fontSize) => 
      string
        .split('')
        .map((c) => c.charCodeAt(0))
        .map((c) => 170 * (fontSize / 1000))
        .reduce((total, width) => total + width, 0);

        function _base64ToArrayBuffer(base64) {
          var binary_string = window.atob(base64.replace(/^data:image\/(png|jpeg|jpg);base64,/, ''));
          var len = binary_string.length;
          var bytes = new Uint8Array(len);
          for (var i = 0; i < len; i++) {
              bytes[i] = binary_string.charCodeAt(i);
          }
          return bytes.buffer;
        }

      /* helper */

      /* writing in pdf */

      const writePdf = async (value = "", size = 10, x = 1, y = 1, font = Font) => {

        let options = {
          size,
          x,
          y,
        }

        if(font)
          options = {...options, font}
        
        firstPg.drawText(value, options)
      }

      await writePdf(objData.name.toUpperCase(), 22, 300, 325, null);
      await writePdf(objData.cpf, 15, 410, 271, null);
      await writePdf(objData.courseTitle.toUpperCase(), 15, 317, 247, null)
      await writePdf(`${objData.totalDuration}`, 15, 417, 204, null)
      await writePdf(objData.conclusioDate, 15, 450, 184, null)
      await writePdf(objData.authCode, 15, 490, 163, null);

      /* writing in pdf */
      
      /* secod page */
      const pngImage = await pdfDoc.embedPng(_base64ToArrayBuffer(objData.qrCode))

      secondPg.drawImage(pngImage, {
        x: 105,
        y: 65,
        width: 170,
        height: 170,
      })

      let options = {
        size: 20,
        x: 130,
        y: 20,
      }

      secondPg.drawText(objData.authCode, options)

      /* secod page */

      /* parsing pdf */

      const pdfBytes = await pdfDoc.save();
      console.log("Done creating");


      const pdfDataUri = await pdfDoc.saveAsBase64({ dataUri: true });
      document.getElementById("pdf").src = pdfDataUri;

      var file = new File(
        [pdfBytes],
        `${name} certificate.pdf`,
        {
          type: "application/pdf;charset=utf-8",
        }
      );

      /* parsing pdf */


      return file;

    };

    const postData = async () =>{

      const cer = await generatePDF();

      let formData = new FormData(document.querySelector('#formCerfi'))

      formData.append('certificate', cer, 'cerfificate.pdf');

      const resp = await fetch(urlFinal, {
        method: 'post',
        headers: {
          "X-CSRF-Token": _token
        },
        body: formData
      })
      .then(async (response) => {
        return response.json();
      })  
      .then(function(json){

        console.log(json);

      })
      .catch(function(error){
        console.log('error', error);

      });


    }


    await generatePDF()
    await postData()


        
  }

  main()
 


</script>

