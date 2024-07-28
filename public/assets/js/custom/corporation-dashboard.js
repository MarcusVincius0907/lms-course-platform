/* CORPORATION DASHBOARD */

console.log("hello");

const studentsUpload = document.getElementById("studentsUpload");
const studentsCorpInput = document.getElementById("studentsCorpInput");
const studentCorpList = document.getElementById("studentCorpList");

function handleFileSelect(event) {
  const reader = new FileReader();
  reader.onload = handleFileLoad;
  reader.readAsText(event.target.files[0]);
}

function handleFileLoad(event) {
  //document.getElementById('fileContent').textContent = event.target.result;
  const json = convertToJson(event.target.result);
  if (json) {
    setInForm(json);
    renderStudents(json);
    Swal.fire("Sucesso", "Arquivo válido", "success");
  } else {
    Swal.fire("Erro", "Arquivo inválido", "error");
  }
}

studentsUpload.addEventListener("change", handleFileSelect, false);

//tratamento do arquivo

function convertToJson(str) {
  let hasError = false;

  let students = [];

  let lines = str.split(/\r\n|\n/);

  //deleting header lines
  lines = lines.slice(8);

  if (lines.length == 0) hasError = true;
  else {
    lines.forEach((line) => {
      let studentData = line.split(",");

      console.log(studentData);

      if (studentData.length < 2) {
        hasError = true;
        return;
      }

      students.push({
        name: studentData[0],
        email: studentData[1].replace(/\s/g, ""),
        cpf: studentData[2].replace(/\s/g, ""),
      });
    });
  }

  if (hasError) return false;

  return JSON.stringify(students);
}

function setInForm(data) {
  studentsCorpInput.value = `${data}`;
}

function renderStudents(data) {
  let tableItems = "";
  let dataArr = JSON.parse(data);
  dataArr.forEach((v, i) => {
    tableItems += `
              <tr> 
                  <td>${i + 1}</td>
                  <td>${v.name}</td>
                  <td>${v.email}</td>
                  <td>${v.cpf}</td>
              </tr>`;
  });

  let table = `
              <div class="table-container">
                  <table class="table table-striped- table-bordered table-hover text-center">
                      <thead>
                      <tr>
                          <th>Index</th>
                          <th>Nome</th>
                          <th>Email</th>
                          <th>CPF</th>
                      </tr>
                      </thead>
                      <tbody>
                          <td></td>
                          <td></td>
                          <td></td>
                          <td>Nenhuma informação encontrada</td>
                      </tbody>
  
                  </table>
                      
              </div>`;

  if (tableItems) {
    table = `
              <div class="table-container">
                  <table class="table table-striped- table-bordered table-hover text-center">
                      <thead>
                      <tr>
                          <th>Index</th>
                          <th>Nome</th>
                          <th>Email</th>
                          <th>CPF</th>
                      </tr>
                      </thead>
                      <tbody>
                          ${tableItems}
                      </tbody>
  
                  </table>
                      
              </div>`;
  }

  studentCorpList.innerHTML = table;
}


const courseCorp = document.querySelector("#courseCorp");

let coursesIdList = [];
let jsonValue = null;
try{
  jsonValue = JSON.parse(courseCorp.value)
}catch(e){
  console.log('error when parsing value', e);
}

if(jsonValue){
  coursesIdList = jsonValue;
}

function selectCourse(idCourse) {
  console.log('here');
  if (coursesIdList.find((el) => el === idCourse)) {
    coursesIdList = coursesIdList.filter((el) => el !== idCourse);
  } else {
    coursesIdList.push(idCourse);
  }

  courseCorp.value = JSON.stringify(coursesIdList);
}

const imgInp = document.querySelector("#imgInp");
const preview = document.querySelector("#preview-image-corp");
imgInp.onchange = (evt) => {
  const [file] = imgInp.files;
  if (file) {
    preview.src = URL.createObjectURL(file);
  }
};

/* CORPORATION DASHBOARD */
