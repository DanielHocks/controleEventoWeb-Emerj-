<?php

require_once('../conexao.php');

    session_start();


    if (!isset($_SESSION['usuarioNome']) or !isset($_SESSION['usuarioId']) or !isset ($_SESSION['usuarioNiveisAcessoId']) or !isset($_SESSION['usuarioEmail'])){

	unset(

		$_SESSION['usuarioId'],

		$_SESSION['usuarioNome'],

		$_SESSION['usuarioNiveisAcessoId'],

		$_SESSION['usuarioEmail']

	);


	//redirecionar o usuario para a página de login

	header("Location: ../index.php");

}

$nivelLogado = $_SESSION['usuarioNiveisAcessoId'];

header("Content-Type: text/html; charset=utf-8", true);

$acao = NULL;

$editFormAction = $_SERVER["PHP_SELF"];

if (isset($_SERVER["QUERY_STRING"])) {

  $editFormAction .= "?" . htmlentities($_SERVER["QUERY_STRING"]);

}


?>

<!DOCTYPE html>

<html>

    <head>

        <link rel="stylesheet" href="../css/cadastrar_eventos.css">
        <link href="https://fonts.googleapis.com/css?family=Bree+Serif&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

		<!--CHAMANDO CSS QUE OCULTA ÍCONE IMPRESSORA-->

		<link rel="stylesheet" type="text/css" href="../css/print.css" media="print"/>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <script src="../js/funcoes_cadastrar.js" type="text/javascript"></script>
        <script src="../js/validador.js" type="text/javascript"></script>
        <script src="../js/jquery.inputmask.bundle.js" ></script>
        <meta http-equiv="X-UA-Compatible" content="chrome=1">
        <meta http-equiv="Content-Type" content="text/html; charset=utf8">

<script language='JavaScript'>

function somenteNumeros(e) {

        var charCode = e.charCode ? e.charCode : e.keyCode;

        // charCode 8 = backspace

        // charCode 9 = tab

        if (charCode != 8 && charCode != 9) {

            // charCode 48 equivale a 0

            // charCode 57 equivale a 9

            if (charCode < 48 || charCode > 57) {

                return false;

            }

        }

    }

function validaCampos(){

    var d = document.form1;

    var cod = document.getElementById('codInscricao').value;

    if (cod == ""){
        alert("O nº de inscrição deve ser preenchido!");
    }

    else if (cod != "" && cod.length < 12){
        alert('Nº de inscrição inválido, verifique se contém 14 digitos!');
    }

    else if (cod != "" && cod.length >= 12){
        document.form1.hdAcao.value = "pesquisar";
        enviaFormulario();
    }

}

function validaCampos2(){

    var codigoEvento = document.getElementById('codigoEvento').value;

    var cpf = document.getElementById('txtMascaraCpf').value;

    //var data = document.getElementById('txtData').value;

    if (codigoEvento == "" && cpf  == "" /* && data == "" */){
        alert("Informe todos os campos !");
    }

    else{
        document.form1.hdAcao.value = "pesquisar2";
        enviaFormulario();
    }
}

// ENVIAR OS DADOS DO FORM

function enviaFormulario(){

    document.form1.submit();

}


function MM_callJS(jsStr) { //v2.0

    return eval(jsStr)
}

// MASCARA PARA O CAMPO CPF
function fMasc(objeto,mascara) {

    obj=objeto

    masc=mascara

    setTimeout("fMascEx()",1)

}

function fMascEx() {

    obj.value=masc(obj.value)

}


function mCPF(cpf){

    cpf=cpf.replace(/\D/g,"")

    cpf=cpf.replace(/(\d{3})(\d)/,"$1.$2")

    cpf=cpf.replace(/(\d{3})(\d)/,"$1.$2")

    cpf=cpf.replace(/(\d{3})(\d{1,2})$/,"$1-$2")

    return cpf

}

</script>

<style>
    @media print {
            form{
                display: none;
            }
        }

    #buscar, #txtMascaraCpf, #campos_cpf {

        display: none;

    }

</style>
</head>

<body>

<div id="title"><center><spam>CONSULTA REGISTRO DO PARTICIPANTE</spam></center></div>

    <br><br>

<?php date_default_timezone_set('America/Sao_Paulo'); ?>

 <div class="container">

     <form id="form1" name="form1" method="post" action="<?php echo $editFormAction; ?>">

        <fieldset>

        <legend>Consulte pelo código de inscrição ou pelo CPF abaixo:</legend>

    <br>

            <div class="row">

                <div class="form-group">

                    <div class="col-md-5 col-xs-6">

                        <br>

                        <input onclick="PegarValor2()" type="radio" name="valor" id="valor" checked>

                        <label style="font-size: 17px;" for="valor">Nº da Inscrição:</label>

                        <input class='form-control' placeholder='14 Digitos' name="codInscricao" id="codInscricao" maxlength="14" type="text">

                    </div>



                    <div class="col-md-2 col-xs-4">

                        <input style="margin-top:45px;" name="inscrever" type="submit" class="textoNormal form-control btn btn-primary" id="inscrever" onClick="MM_callJS('validaCampos();')" value="Pesquisa"/>

                        <input name="hdAcao" type="hidden" id="hdAcao" value="<?php if (isset($_SESSION["codInscricao"])) echo('alterar'); else  echo('inserir') ?>"/>

                        <input name="codEvento" type="hidden" id="codEvento" />

                    </div>

                </div>

            </div>

            <br>

            <!-- PESQUISA PELO Nº DE CPF, CASO FOR SELECIONADO PELO USUÁRIO -->

            <div class="row">
                <div class="form-group">
                    <div class="col-md-3 col-xs-6">

                        <input  onclick="PegarValor2()" type="radio" name="valor" id="valor2"><label style="font-size: 17px;" for="valor2"> &nbsp Nº do CPF: </label>
                        <input maxlength="14" onkeydown="javascript: fMasc( this, mCPF );" disabled type="text" class="form-control" type="text" id="txtMascaraCpf" name="txtMascaraCpf" placeholder="Somente Número do CPF" value="<?php if (isset($_POST['txtMascaraCpf'])) echo($_POST['txtMascaraCpf']); ?>">

                    </div>
                </div>
            </div>

            <br>

            <div id="campos_cpf"><!-- DIV PARA DAR DISPLAY NONE NOS ELEMENTOS DO CPF -->

                <div class="row">
                    <div class="form-group">
                        <div class="col-md-3 col-xs-6">

                            <label for="codInscricao">Código Evento:</label> 
                            <input class='form-control' placeholder='Código Evento' name="codigoEvento" id="codigoEvento" type="text" class="textoNormal"/>
                            <!--<p style="margin-left: 220px; margin-top: -25px; color: blue;"><b>ou </b></p>-->
                        </div>
<br>
<br><br><br>



                        <div class="col-md-3 col-xs-6">
                            <label for="codInscricao">Busca por Período - data inicial</label>
                            <input class='form-control' onkeydown="javascript:maskData('dataEventoInicio')" placeholder='Data inicial' name="dataEventoInicio" id="dataEventoInicio" type="text" class="textoNormal" maxlength="10" />
                        </div>

                        <div class="col-md-3 col-xs-6">
                            <label for="codInscricao">Busca por Período - data final</label>
                            <input class='form-control' onkeydown="javascript:maskData('dataEventoFinal')" placeholder='Data final' name="dataEventoFinal" id="dataEventoFinal" type="text" class="textoNormal" maxlength="10" />

                        </div>
                    <!--  Retirado Campo de busca por data
                        <div class="col-md-3 col-xs-5">

                            <label for="codInscricao">Data:</label>

                            <input class='form-control textoNormal' placeholder='data do evento' name="txtData" id="txtData" type="text" onKeyPress="mascara_data(event, this.value);" maxlength="10"/>

                        </div>
                    -->


                        <div class="col-md-2 col-xs-6">

                            <input style="margin-top: 65px; margin-left: -90px;" class="form-control btn btn-primary" type="button" id="buscar" name="buscar" onClick="MM_callJS('validaCampos2();')" value="Pesquisar (CPF)">

                        </div>

                    </div>

                </div>

            </div>

    <br><br>

       </fieldset>

     </form>

    </div>

<br/>



   <div class="container">

<script type="text/javascript">
  /** 
   @Geraldo Função para preenchimento da mascara da data 00/00/0000 no campo data do form
**/

  function maskData(data){
    obj = document.getElementById(data);
    vl = obj.value;
    l = vl.toString().length;
    
    switch(l){
      case 2:
      obj.value = vl+"/";
      break;
      case 5:
      obj.value = vl+"/";
      break;
    }
  }

</script>

<?php

/*
 =====================================================================================================
 @Geraldo Implementação da para buscar por periodo listando os eventos do usuario
 ======================================================================================================
*/
if(!empty($_POST['txtMascaraCpf']) && !empty($_POST['dataEventoInicio']) && !empty($_POST['dataEventoFinal'])){


      $dataEventoInicio = $_POST["dataEventoInicio"];   // pega primeira data do form data
      $anoInvertidaInicio = substr($dataEventoInicio, -4);//Função pegar ano
      $mesInvertidoInicio = substr($dataEventoInicio, 3, 2);//Função pegar mês
      $diaInvertidoInicio = substr($dataEventoInicio, 0, 2 );////Função pegar dia
      $dataInvertidaInicio = $anoInvertidaInicio . "-". $mesInvertidoInicio ."-". $diaInvertidoInicio;


      $dataEventoFim = $_POST["dataEventoFinal"];       // pega segunda data do form data
      $anoInvertidaFim = substr($dataEventoFim, -4);//Função pegar ano
      $mesInvertidoFim = substr($dataEventoFim, 3, 2);//Função pegar mês
      $diaInvertidoFim = substr($dataEventoFim, 0, 2 );////Função pegar dia
      $dataInvertidaFim = $anoInvertidaFim . "-". $mesInvertidoFim ."-". $diaInvertidoFim;


    $cpf = str_replace("-","",(str_replace(".","", $_POST['txtMascaraCpf'])));


    $sqlQuery = "select distinct e.codigo, e.nome, pa.nome, pa.codigo, pa.cpf, po.data
                 from evento e, porta po, participante pa
                 where po.data between '$dataInvertidaInicio' and '$dataInvertidaFim'  
                                                    AND po.codEvento = e.codigo AND pa.cpf = '$cpf' ";
    $sqlResult = mysqli_query($conn, $sqlQuery) or die(mysqli_error($conn));


    if(mysqli_num_rows($sqlResult) == 0){
        echo "<strong><b><h3>Não existe resultado para sua busca </h3></b></strong>";

    }else{



        echo "<table class='TDtable1 table table-striped' align='center' border=1 cellpadding='1' cellspacing='1'>";
            echo '<form method="POST" action="">';


            echo "<tr class='TDdata'>";
            echo "<tr class='TDtable1' style='background-color:#337AB7; color:white;' >";
                echo "<td colspan='6' class='TDtable1' align='center' ><h3>Selecionar Evento do participante: </h3></td>";
            echo "</tr>";
            echo "</tr>";
            echo "<tr class='TDdata' style='background-color:#337AB7; color:white;' >";
                echo "  <td style = 'width:3%' align='center'>Selecionar</td>";
                echo "  <td style=  'width:'3%' align='center'>Cod.Evento</td>";
                echo "  <td style = 'width:40%' align='center'>Nome Evento</td>";
                echo "  <td style = 'width:9%' align='center'>Data Evento</td>";
                echo "  <td style = 'width:30%' align='center'>Participante</td>";
                echo "  <td style = 'width:15%' align='center'>Código Participante</td>";

            echo "</tr>";

         while($resultadoBusca = mysqli_fetch_row($sqlResult)){
            $resultadoBusca[5] = implode("/",array_reverse(explode("-",$resultadoBusca[5])));

            echo "<tr class='TDtable1'>";
                echo "<td class='TDtable1' align='center'>";
                  echo "<input type='radio' name='codEventoParticiante' value=' ".$resultadoBusca[0] ."."
                                                                                 .$resultadoBusca[3] ."."
                                                                                 .$resultadoBusca[4] ." ' > </td>";

                  echo "<td class='TDtable1' align='center'>$resultadoBusca[0]</td>";
                  echo "<td class='TDtable1' align='center'>$resultadoBusca[1]</td>";
                  echo "<td class='TDtable1' align='center'>$resultadoBusca[5]</td>";
                  echo "<td class='TDtable1' align='left'>$resultadoBusca[2]</td>";
                  echo "<td class='TDtable1' align='center'>$resultadoBusca[3]</td>";
      }
                  echo "</tr>";
        echo "</table>";

        echo "<table>";
                echo "<tr>";
                  echo "<td >";
                    echo "<input style='margin-top: 25px;' class='form-control btn btn-primary' type='submit'  value='Listar dados' name='buscaRadioParticipante' id='buscaRadioParticipante' />";
                  echo "</td>";
                echo "</tr>";

              echo "</form>";
            echo "</table>";

    }


}
/*
 =====================================================================================================
 @Geraldo - buscar o post radio contedo codigo do evento e participante (codigo pego implementado por daniel)
 ======================================================================================================
*/
if (isset($_POST['codEventoParticiante'])){
    $codigoEventoParticipanteCPF = explode(".",trim(($_POST['codEventoParticiante'])));
    $codigoEvento = $codigoEventoParticipanteCPF[0]; //post do radio posição 0
    $cpf = $codigoEventoParticipanteCPF[2];// post do radio posição 2
   //post do radio posição 1 é o codigo do participante


    mysqli_select_db($conn, "emerjco_eventos");

           $queryEvento = strtolower(trim(stripslashes('select distinct e.codigo, e.nome, pa.nome as participante, pa.codigo, p.data, pa.cpf from evento e, porta p, participante pa, inscricoes i 
           where e.codigo = "' . $codigoEvento . '" AND p.codEvento ="'. $codigoEvento . '" AND pa.cpf ="' . $cpf .'"')));

           $result = mysqli_query($conn, $queryEvento) or die(mysqli_error($conn));
           $result2 = mysqli_query($conn, $queryEvento) or die(mysqli_error($conn));

           $rowResultado2 = mysqli_fetch_row($result2);
           $num_row = mysqli_num_rows($result);

           if ($num_row == 0) {

               echo("<script>alert('Não há inscrição no evento.');</script>");


           } else if ($num_row != 0) {

           echo("<br><br><center><p style='font-size:20px;'><strong>Dados do Evento e Participante</strong></p></center><br>");

           echo("<p style='font-size:20px;'>Nome do Participante: $rowResultado2[2] </p><br>");
           echo("<p style='font-size:20px;'>CPF: $rowResultado2[5] </p><br>");

           while ($rowResultado = mysqli_fetch_row($result)) {


                   //$padraoFormatadoData = date('d/m/Y', strtotime($rowResultado[2]));


                   //Tabela com resultado Evento e do Participante
                   $padraoFormatadoData = date('d/m/Y', strtotime($rowResultado[4]));

                   echo("<table border=1 class='table table-striped' style='border:1px solid black'>");
                   echo("<tr style='background-color:#337AB7; color:white;'>

            <th >Código do evento</th>

            <th>Evento</th>

            <th>Data do evento</th>

            <th>Código participante</th>   

          </tr>
          

          <tr>

            <td>" . $rowResultado[0] . "</td>

            <td>" . $rowResultado[1] . "</td>

            <td>" . $padraoFormatadoData . "</td>

            <td>" . $rowResultado[3] . "</td>

          </tr>

          </table>

          <br>");

                   $dataDoEvento = $rowResultado[4];

                   $timestamp = strtotime($dataDoEvento . "-365 days");

                   //echo "1 ano atrás: " . date('d/m/Y', $timestamp);


                   // Calcula a data atual daqui 1 ano atrás

                   $dataAtual = date("Y/m/d");

                   $timestamp1Ano = strtotime($dataAtual . "-365 days");

                   $umAnoAntes = date('Y-m-d', $timestamp1Ano);


                   if ($dataDoEvento <= $umAnoAntes) {

                       echo "<p align='center' style='font-size:15px; color:#900';'><strong>Evento ocorreu há mais de 1 ano atrás.</strong></p>";

                   }

                   $dataAtual = date("Y/m/d");

                   if ($rowResultado[4] > $dataAtual) {

                       echo "Evento ainda não ocorreu.";

                   }

               }
           }


       mysqli_select_db($conn, "emerjco_eventos");

       $queryInscricoes = strtolower(trim(stripslashes('select codEvento, codParticipante from inscricoes where codParticipante = "' . $rowResultado2[3] . '" and codEvento = "' . $rowResultado2[0] . '"')));

       $resultInscricoes = mysqli_query($conn, $queryInscricoes) or die(mysqli_error($conn));

       $totalRegistrosInscricoes = mysqli_num_rows($resultInscricoes);


       if ($totalRegistrosInscricoes == 0){

           echo("<script> alert('Não há inscrição no evento.');</script>");

       }

       else if ($totalRegistrosInscricoes != 0){

       /*Não mexer*/

       mysqli_select_db($conn, "emerjco_eventos");

       $queryRegistroPonto = strtolower(trim(stripslashes("select data, hora from registroponto where codParticipante = " . $rowResultado2[3] . " and codEvento = " . $rowResultado2[0] . " ORDER BY data, hora ASC")));

       $resultRegistroPonto = mysqli_query($conn, $queryRegistroPonto) or die(mysqli_error($conn));

       $row_RegistroPonto = mysqli_fetch_row($resultRegistroPonto);

       $totalRegistrosRegistroPonto = mysqli_num_rows($resultRegistroPonto);


       if ($totalRegistrosRegistroPonto == 0){


           echo("<p style='font-size:16px; color:#900';'>Não há registros para o número de inscrição.</p>");


       } else if ($totalRegistrosRegistroPonto != 0){

       ?>


            <div align=center>


                <?php


                echo "<p style='font-size:20px;'><strong>Ponto</strong></p>";

                echo "<br>";

                echo("<table class='table table-striped col-md-6' cellpadding='10px' border=1 style='border:#999 1px solid; border-collapse:collapse; padding:15px; font-size:15px;'>\n");


                echo("\t<tr style='background-color:#337AB7; color:white;'>\n");

                for ($n = 0; $n < 1; $n++) {

                    echo("\t\t<td align='center'>&nbsp;<b>" . "Data" . "</b>&nbsp;</td>\n");

                    echo("\t\t<td align='center'>&nbsp;<b>" . "Hora" . "</b>&nbsp;</td>\n");

                }

                echo("\t</tr>\n");


                for ($i = 0; $i < $totalRegistrosRegistroPonto; $i++) {

                    echo("\t<tr>\n");

                    for ($n = 0; $n < 1; $n++) {


                        $padraoFormatoData = date('d/m/Y', strtotime($row_RegistroPonto[0]));


                        echo("\t\t<td align='center' style:'padding:15px;'>&nbsp;");


                        echo $padraoFormatoData;


                        echo("&nbsp;</td>\n");


                        echo("\t\t<td align='center' style:'padding:15px;'>&nbsp;" . $row_RegistroPonto[1] . "&nbsp;</td>\n");

                    }

                    $row_RegistroPonto = mysqli_fetch_row($resultRegistroPonto);

                    echo("\t</tr>\n");

                }

                echo("</table>");

                }

                echo "<br><br>";


                /**/

                mysqli_select_db($conn, "emerjco_eventos");

                $queryFrequencia = strtolower(trim(stripslashes("select distinct f.codEvento, f.codParticipante, f.dataPorta, f.horaPorta, f.cargaHorariaOAB, f.permanencia, f.percentual, p.horaFim from frequencia2 f, porta p where f.codParticipante = " . $rowResultado2[3] . " and f.codEvento = " . $rowResultado2[0] . " and (f.codEvento = p.codEvento) and (f.horaPorta = p.horaInicio) ORDER BY f.dataPorta, f.horaPorta ASC")));

                $resultFrequencia = mysqli_query($conn, $queryFrequencia) or die(mysqli_error($conn));

                $row_Frequencia = mysqli_fetch_row($resultFrequencia);

                $totalRegistrosFrequencia = mysqli_num_rows($resultFrequencia);


                $dataAtual = date("Y/m/d");


                if ($totalRegistrosFrequencia == 0) {


                    echo("<p style='font-size:16px; color:#900';'>Não há apuração para o número de inscrição.</p>");


                } else if ($totalRegistrosFrequencia != 0) {


                    echo "<p style='font-size:20px;'><strong>Apuração</strong></p>";

                    echo "<br>";

                    echo("<table class='table table-striped cellpadding='10px' border=1 style='border:#999 1px solid; border-collapse:collapse; padding:15px; font-size:15px;'>\n");


                    echo("\t<tr style='background-color:#337AB7; color:white;'>\n");

                    for ($n = 0; $n < 1; $n++) {

                        echo("\t\t<td align='center'>&nbsp;<b>" . "Data" . "</b>&nbsp;</td>\n");

                        echo("\t\t<td align='center'>&nbsp;<b>" . "Hora Inicial" . "</b>&nbsp;</td>\n");

                        echo("\t\t<td align='center'>&nbsp;<b>" . "Hora Final" . "</b>&nbsp;</td>\n");

                        echo("\t\t<td align='center'>&nbsp;<b>" . "Permanência" . "</b>&nbsp;</td>\n");

                        echo("\t\t<td align='center'>&nbsp;<b>" . "Percentual" . "</b>&nbsp;</td>\n");

                        echo("\t\t<td align='center'>&nbsp;<b>" . "Carga Horária OAB" . "</b>&nbsp;</td>\n");

                    }

                    echo("\t</tr>\n");


                    for ($i = 0; $i < $totalRegistrosFrequencia; $i++) {

                        echo("\t<tr>\n");

                        for ($n = 0; $n < 1; $n++) {


                            $padraoFormatoData = date('d/m/Y', strtotime($row_Frequencia[2]));


                            echo("\t\t<td align='center' style:'padding:15px;'>&nbsp;" . $padraoFormatoData . "&nbsp;</td>\n");

                            echo("\t\t<td align='center' style:'padding:15px;'>&nbsp;" . $row_Frequencia[3] . "&nbsp;</td>\n");

                            echo("\t\t<td align='center' style:'padding:15px;'>&nbsp;" . $row_Frequencia[7] . "&nbsp;</td>\n");

                            echo("\t\t<td align='center' style:'padding:15px;'>&nbsp;" . $row_Frequencia[5] . "&nbsp;</td>\n");


                            $percentualPortas = $row_Frequencia[6] * 100;

                            $percentualPortasFormatado = round($percentualPortas, 2);


                            echo("\t\t<td align='center' style:'padding:15px;'>&nbsp;" . $percentualPortasFormatado . " %&nbsp;</td>\n");

                            echo("\t\t<td align='center' style:'padding:15px;'>&nbsp;" . $row_Frequencia[4] . "&nbsp;</td>\n");

                        }

                        $row_Frequencia = mysqli_fetch_row($resultFrequencia);

                        echo("\t</tr>\n");

                    }

                    echo("</table>");


                    echo "<br><br>";

                    echo "<strong>Tipo de presença:</strong> 1 - Normal / 2 - Manual / 3 - Videoconferência / 4 - Requerimento";

                    echo "<br><br>";


                }

                }
            }


/*****************************************************************************
 *               FIM IMPLEMENTAÇÃO FEITO POR @GERALDO                        *
 ****************************************************************************/
?>








<?php

/*****************************************************************************
 *                    CONSULTA PELO CODINSCRICAO                             *
 ****************************************************************************/

	if ((!empty($_POST["hdAcao"])) && ($_POST["hdAcao"] == "pesquisar")) {

		$codInscricao = $_POST["codInscricao"];

		$tamanhocodInscricao = strlen($codInscricao);

		$codigoEvento = substr($codInscricao, 2, 4);


		if ($tamanhocodInscricao == 12) {

			/* se o código com 12 dígitos */

			$codigoParticipante = substr($codInscricao, -6, 7);


		} else if ($tamanhocodInscricao == 14) {

			/* se o código com 14 dígitos */

			$codigoParticipante = substr($codInscricao, -7, 8);


		} else if ($tamanhocodInscricao == 0){

            echo "<script>alert('É necessário informar um código de inscrição!')</script>";

		}else if ($tamanhocodInscricao < 12){

            echo "<script>alert('Número de inscrição inválido!')</script>";

            return false;



        }


	mysqli_select_db($conn, "emerjco_eventos");

	$queryEvento = strtolower(trim(stripslashes('select distinct e.codigo, e.nome, pa.nome as participante, pa.codigo, p.data, pa.cpf from evento e, porta p, participante pa, inscricoes i 
    where e.codigo = "' . $codigoEvento . '" AND p.codEvento ="'. $codigoEvento . '" AND pa.codigo ="' . $codigoParticipante .'"')));

	$result = mysqli_query($conn, $queryEvento) or die(mysqli_error($conn));
	$result2 = mysqli_query($conn, $queryEvento) or die(mysqli_error($conn));
    $rowResultado2 = mysqli_fetch_assoc($result2);

    echo("<br><br><center><p style='font-size:20px;'><strong>Dados do Evento e Participante</strong></p></center><br>");


    echo("<p style='font-size:20px;'>Nome do Participante: " . $rowResultado2["participante"] . " </p><br>");
    echo("<p style='font-size:20px;'>CPF: " . $rowResultado2["cpf"] ."  </p><br>");

	while ($rowResultado = mysqli_fetch_row($result)) {

    if ($rowResultado == 0){

    	echo("<script>alert('Não há inscrição no evento.');</script>");

	}

	else if ($rowResultado != 0){

     $padraoFormatadoData = date('d/m/Y', strtotime($rowResultado[4]));

        //Tabela com resultado Evento e do Participante

	echo("<table border=1 class='table table-striped' style='border:1px solid black'>");

    echo("<tr style='background-color:#337AB7; color:white;'>

            <th >Código do evento</th>

            <th>Evento</th>

            <th>Data do evento</th>

            <th>Código participante</th>   

          </tr>

            

          <tr>

            <td>" . $rowResultado[0]     . "</td>

            <td>" . $rowResultado[1]     . "</td>

            <td>" . $padraoFormatadoData . "</td>

            <td>" . $rowResultado[3]     . "</td>

          </tr>

          

          </table>

          

          <br>");


        $dataDoEvento = $rowResultado[4];

        $timestamp = strtotime($dataDoEvento . "-365 days");

        //echo "1 ano atrás: " . date('d/m/Y', $timestamp);


        // Calcula a data atual daqui 1 ano atrás

        $dataAtual = date("Y/m/d");

        $timestamp1Ano = strtotime($dataAtual . "-365 days");

        $umAnoAntes = date('Y-m-d', $timestamp1Ano);


        if ($dataDoEvento <= $umAnoAntes){

            echo "<p align='center' style='font-size:15px; color:#900';'><strong>Evento ocorreu há mais de 1 ano atrás.</strong></p>";

        }

          $dataAtual = date("Y/m/d");

          if ($rowResultado[4] > $dataAtual){

              echo "Evento ainda não ocorreu.";

             }

	      }

        }

        mysqli_select_db($conn, "emerjco_eventos");

        $queryInscricoes = strtolower(trim(stripslashes("select codEvento, codParticipante from inscricoes where codParticipante = " . $codigoParticipante . " and codEvento = " . $codigoEvento)));

        $resultInscricoes = mysqli_query($conn, $queryInscricoes) or die(mysqli_error($conn));

        $totalRegistrosInscricoes = mysqli_num_rows($resultInscricoes);



        if ($totalRegistrosInscricoes == 0){

            echo("<script> alert('Não há inscrição no evento.');</script>");

        }

        else if ($totalRegistrosInscricoes != 0){

		/*Não mexer*/

		mysqli_select_db($conn, "emerjco_eventos");

		$queryRegistroPonto = strtolower(trim(stripslashes("select data, hora from registroponto where codParticipante = " . $codigoParticipante . " and codEvento = " . $codigoEvento . " ORDER BY data, hora ASC")));

		$resultRegistroPonto = mysqli_query($conn, $queryRegistroPonto) or die(mysqli_error($conn));

		$row_RegistroPonto = mysqli_fetch_row($resultRegistroPonto);

		$totalRegistrosRegistroPonto = mysqli_num_rows($resultRegistroPonto);



		if ($totalRegistrosRegistroPonto == 0){



		echo ("<p style='font-size:16px; color:#900';'>Não há registros para o número de inscrição.</p>");



		} else if ($totalRegistrosRegistroPonto != 0){

       ?>

        <div align=center>


       <?php


		echo "<p style='font-size:20px;'><strong>Ponto</strong></p>";

		echo "<br>";

		echo("<table class='table table-striped col-md-6' cellpadding='10px' border=1 style='border:#999 1px solid; border-collapse:collapse; padding:15px; font-size:15px;'>\n");



		echo("\t<tr style='background-color:#337AB7; color:white;'>\n");

	for ($n=0; $n<1; $n++) {

		echo("\t\t<td align='center'>&nbsp;<b>" . "Data" . "</b>&nbsp;</td>\n");

		echo("\t\t<td align='center'>&nbsp;<b>" . "Hora" . "</b>&nbsp;</td>\n");

	}

	echo("\t</tr>\n");



	for ($i=0; $i<$totalRegistrosRegistroPonto; $i++) {

		echo("\t<tr>\n");

		for ($n=0; $n<1; $n++) {

			$padraoFormatoData = date('d/m/Y', strtotime($row_RegistroPonto[0]));


			echo("\t\t<td align='center' style:'padding:15px;'>&nbsp;");



				echo $padraoFormatoData;



			echo("&nbsp;</td>\n");



			echo("\t\t<td align='center' style:'padding:15px;'>&nbsp;" . $row_RegistroPonto[1] . "&nbsp;</td>\n");

		}

		$row_RegistroPonto = mysqli_fetch_row($resultRegistroPonto);

		echo("\t</tr>\n");

	}

	echo("</table>");

	}

	echo "<br><br>";



		/**/

		mysqli_select_db($conn, "emerjco_eventos");

		$queryFrequencia = strtolower(trim(stripslashes("select distinct f.codEvento, f.codParticipante, f.dataPorta, f.horaPorta, f.cargaHorariaOAB, f.permanencia, f.percentual, p.horaFim from frequencia2 f, porta p where f.codParticipante = " . $codigoParticipante . " and f.codEvento = " . $codigoEvento . " and (f.codEvento = p.codEvento) and (f.horaPorta = p.horaInicio) ORDER BY f.dataPorta, f.horaPorta ASC")));

		$resultFrequencia = mysqli_query($conn, $queryFrequencia) or die(mysqli_error($conn));

		$row_Frequencia = mysqli_fetch_row($resultFrequencia);

		$totalRegistrosFrequencia = mysqli_num_rows($resultFrequencia);



		$dataAtual = date("Y/m/d");



	if ($totalRegistrosFrequencia == 0){



		echo ("<p style='font-size:16px; color:#900';'>Não há apuração para o número de inscrição.</p>");



	} else if ($totalRegistrosFrequencia != 0){



		echo "<p style='font-size:20px;'><strong>Apuração</strong></p>";

		echo "<br>";

		echo("<table class='table table-striped cellpadding='10px' border=1 style='border:#999 1px solid; border-collapse:collapse; padding:15px; font-size:15px;'>\n");



		echo("\t<tr style='background-color:#337AB7; color:white;'>\n");

	for ($n=0; $n<1; $n++) {

		echo("\t\t<td align='center'>&nbsp;<b>" . "Data" . "</b>&nbsp;</td>\n");

		echo("\t\t<td align='center'>&nbsp;<b>" . "Hora Inicial" . "</b>&nbsp;</td>\n");

		echo("\t\t<td align='center'>&nbsp;<b>" . "Hora Final" . "</b>&nbsp;</td>\n");

		echo("\t\t<td align='center'>&nbsp;<b>" . "Permanência" . "</b>&nbsp;</td>\n");

		echo("\t\t<td align='center'>&nbsp;<b>" . "Percentual" . "</b>&nbsp;</td>\n");

		echo("\t\t<td align='center'>&nbsp;<b>" . "Carga Horária OAB" . "</b>&nbsp;</td>\n");

	}

	echo("\t</tr>\n");



	for ($i=0; $i<$totalRegistrosFrequencia; $i++) {

		echo("\t<tr>\n");

		for ($n=0; $n<1; $n++) {



			$padraoFormatoData = date('d/m/Y', strtotime($row_Frequencia[2]));



			echo("\t\t<td align='center' style:'padding:15px;'>&nbsp;" . $padraoFormatoData . "&nbsp;</td>\n");

			echo("\t\t<td align='center' style:'padding:15px;'>&nbsp;" . $row_Frequencia[3] . "&nbsp;</td>\n");

			echo("\t\t<td align='center' style:'padding:15px;'>&nbsp;" . $row_Frequencia[7] . "&nbsp;</td>\n");

			echo("\t\t<td align='center' style:'padding:15px;'>&nbsp;" . $row_Frequencia[5] . "&nbsp;</td>\n");



			$percentualPortas = $row_Frequencia[6] * 100;

			$percentualPortasFormatado = round($percentualPortas,2);



			echo("\t\t<td align='center' style:'padding:15px;'>&nbsp;" . $percentualPortasFormatado . " %&nbsp;</td>\n");

			echo("\t\t<td align='center' style:'padding:15px;'>&nbsp;" . $row_Frequencia[4] . "&nbsp;</td>\n");

		}

		$row_Frequencia = mysqli_fetch_row($resultFrequencia);

		echo("\t</tr>\n");

	}

	echo("</table>");



	echo "<br><br>";

	echo "<strong>Tipo de presença:</strong> 1 - Normal / 2 - Manual / 3 - Videoconferência / 4 - Requerimento";

	echo "<br><br>";


	}

	}

}

       /*****************************************************************************
        *                           CONTULTA PELO CPF                               *
        ****************************************************************************/

       if ((!empty($_POST["hdAcao"])) && ($_POST["hdAcao"] == "pesquisar2")) {

            /* retirado função data

           $dataInvertida = $_POST["txtData"];

           $retornaAnoInvertida = substr($dataInvertida, -4);

           $retornaMesInvertida = substr($dataInvertida, 2, -4);

           $retornaDiaInvertida = substr($dataInvertida, 0, 2);

           $retornaDataInvertida = $retornaAnoInvertida . $retornaMesInvertida . $retornaDiaInvertida;

           str_replace("/","",$retornaDataInvertida);
*/

       if(!empty($_POST['txtMascaraCpf']) && !empty($_POST['codigoEvento'])){

           $cpf = str_replace("-","",(str_replace(".","", $_POST['txtMascaraCpf'])));

           $codigoEventoQuery =$_POST['codigoEvento'];

           mysqli_select_db($conn, "emerjco_eventos");

           $queryEvento = strtolower(trim(stripslashes('select distinct e.codigo, e.nome, pa.nome as participante, pa.codigo, p.data, pa.cpf from evento e, porta p, participante pa, inscricoes i 
           where e.codigo = "' . $codigoEventoQuery . '" AND p.codEvento ="'. $codigoEventoQuery . '" AND pa.cpf ="' . $cpf .'"')));

           $result = mysqli_query($conn, $queryEvento) or die(mysqli_error($conn));
           $result2 = mysqli_query($conn, $queryEvento) or die(mysqli_error($conn));

           $rowResultado2 = mysqli_fetch_row($result2);
           $num_row = mysqli_num_rows($result);

           if ($num_row == 0) {

               echo("<script>alert('Não há inscrição no evento.');</script>");


           } else if ($num_row != 0) {

           echo("<br><br><center><p style='font-size:20px;'><strong>Dados do Evento e Participante</strong></p></center><br>");

           echo("<p style='font-size:20px;'>Nome do Participante: $rowResultado2[2] </p><br>");
           echo("<p style='font-size:20px;'>CPF: $rowResultado2[5] </p><br>");

           while ($rowResultado = mysqli_fetch_row($result)) {


                 //  $padraoFormatadoData = date('d/m/Y', strtotime($rowResultado[2]));


                   //Tabela com resultado Evento e do Participante
                   $padraoFormatadoData = date('d/m/Y', strtotime($rowResultado[4]));

                   echo("<table border=1 class='table table-striped' style='border:1px solid black'>");
                   echo("<tr style='background-color:#337AB7; color:white;'>

            <th >Código do evento</th>

            <th>Evento</th>

            <th>Data do evento</th>

            <th>Código participante</th>   

          </tr>
          

          <tr>

            <td>" . $rowResultado[0] . "</td>

            <td>" . $rowResultado[1] . "</td>

            <td>" . $padraoFormatadoData . "</td>

            <td>" . $rowResultado[3] . "</td>

          </tr>

          </table>

          <br>");

                   $dataDoEvento = $rowResultado[4];

                   $timestamp = strtotime($dataDoEvento . "-365 days");

                   //echo "1 ano atrás: " . date('d/m/Y', $timestamp);


                   // Calcula a data atual daqui 1 ano atrás

                   $dataAtual = date("Y/m/d");

                   $timestamp1Ano = strtotime($dataAtual . "-365 days");

                   $umAnoAntes = date('Y-m-d', $timestamp1Ano);


                   if ($dataDoEvento <= $umAnoAntes) {

                       echo "<p align='center' style='font-size:15px; color:#900';'><strong>Evento ocorreu há mais de 1 ano atrás.</strong></p>";

                   }

                   $dataAtual = date("Y/m/d");

                   if ($rowResultado[4] > $dataAtual) {

                       echo "Evento ainda não ocorreu.";

                   }

               }
           }


       mysqli_select_db($conn, "emerjco_eventos");

       $queryInscricoes = strtolower(trim(stripslashes('select codEvento, codParticipante from inscricoes where codParticipante = "' . $rowResultado2[3] . '" and codEvento = "' . $rowResultado2[0] . '"')));

       $resultInscricoes = mysqli_query($conn, $queryInscricoes) or die(mysqli_error($conn));

       $totalRegistrosInscricoes = mysqli_num_rows($resultInscricoes);


       if ($totalRegistrosInscricoes == 0){

           echo("<script> alert('Não há inscrição no evento.');</script>");

       }

       else if ($totalRegistrosInscricoes != 0){

       /*Não mexer*/

       mysqli_select_db($conn, "emerjco_eventos");

       $queryRegistroPonto = strtolower(trim(stripslashes("select data, hora from registroponto where codParticipante = " . $rowResultado2[3] . " and codEvento = " . $rowResultado2[0] . " ORDER BY data, hora ASC")));

       $resultRegistroPonto = mysqli_query($conn, $queryRegistroPonto) or die(mysqli_error($conn));

       $row_RegistroPonto = mysqli_fetch_row($resultRegistroPonto);

       $totalRegistrosRegistroPonto = mysqli_num_rows($resultRegistroPonto);


       if ($totalRegistrosRegistroPonto == 0){


           echo("<p style='font-size:16px; color:#900';'>Não há registros para o número de inscrição.</p>");


       } else if ($totalRegistrosRegistroPonto != 0){

       ?>


            <div align=center>


                <?php


                echo "<p style='font-size:20px;'><strong>Ponto</strong></p>";

                echo "<br>";

                echo("<table class='table table-striped col-md-6' cellpadding='10px' border=1 style='border:#999 1px solid; border-collapse:collapse; padding:15px; font-size:15px;'>\n");


                echo("\t<tr style='background-color:#337AB7; color:white;'>\n");

                for ($n = 0; $n < 1; $n++) {

                    echo("\t\t<td align='center'>&nbsp;<b>" . "Data" . "</b>&nbsp;</td>\n");

                    echo("\t\t<td align='center'>&nbsp;<b>" . "Hora" . "</b>&nbsp;</td>\n");

                }

                echo("\t</tr>\n");


                for ($i = 0; $i < $totalRegistrosRegistroPonto; $i++) {

                    echo("\t<tr>\n");

                    for ($n = 0; $n < 1; $n++) {


                        $padraoFormatoData = date('d/m/Y', strtotime($row_RegistroPonto[0]));


                        echo("\t\t<td align='center' style:'padding:15px;'>&nbsp;");


                        echo $padraoFormatoData;


                        echo("&nbsp;</td>\n");


                        echo("\t\t<td align='center' style:'padding:15px;'>&nbsp;" . $row_RegistroPonto[1] . "&nbsp;</td>\n");

                    }

                    $row_RegistroPonto = mysqli_fetch_row($resultRegistroPonto);

                    echo("\t</tr>\n");

                }

                echo("</table>");

                }

                echo "<br><br>";


                /**/

                mysqli_select_db($conn, "emerjco_eventos");

                $queryFrequencia = strtolower(trim(stripslashes("select distinct f.codEvento, f.codParticipante, f.dataPorta, f.horaPorta, f.cargaHorariaOAB, f.permanencia, f.percentual, p.horaFim from frequencia2 f, porta p where f.codParticipante = " . $rowResultado2[3] . " and f.codEvento = " . $rowResultado2[0] . " and (f.codEvento = p.codEvento) and (f.horaPorta = p.horaInicio) ORDER BY f.dataPorta, f.horaPorta ASC")));

                $resultFrequencia = mysqli_query($conn, $queryFrequencia) or die(mysqli_error($conn));

                $row_Frequencia = mysqli_fetch_row($resultFrequencia);

                $totalRegistrosFrequencia = mysqli_num_rows($resultFrequencia);


                $dataAtual = date("Y/m/d");


                if ($totalRegistrosFrequencia == 0) {


                    echo("<p style='font-size:16px; color:#900';'>Não há apuração para o número de inscrição.</p>");


                } else if ($totalRegistrosFrequencia != 0) {


                    echo "<p style='font-size:20px;'><strong>Apuração</strong></p>";

                    echo "<br>";

                    echo("<table class='table table-striped cellpadding='10px' border=1 style='border:#999 1px solid; border-collapse:collapse; padding:15px; font-size:15px;'>\n");


                    echo("\t<tr style='background-color:#337AB7; color:white;'>\n");

                    for ($n = 0; $n < 1; $n++) {

                        echo("\t\t<td align='center'>&nbsp;<b>" . "Data" . "</b>&nbsp;</td>\n");

                        echo("\t\t<td align='center'>&nbsp;<b>" . "Hora Inicial" . "</b>&nbsp;</td>\n");

                        echo("\t\t<td align='center'>&nbsp;<b>" . "Hora Final" . "</b>&nbsp;</td>\n");

                        echo("\t\t<td align='center'>&nbsp;<b>" . "Permanência" . "</b>&nbsp;</td>\n");

                        echo("\t\t<td align='center'>&nbsp;<b>" . "Percentual" . "</b>&nbsp;</td>\n");

                        echo("\t\t<td align='center'>&nbsp;<b>" . "Carga Horária OAB" . "</b>&nbsp;</td>\n");

                    }

                    echo("\t</tr>\n");


                    for ($i = 0; $i < $totalRegistrosFrequencia; $i++) {

                        echo("\t<tr>\n");

                        for ($n = 0; $n < 1; $n++) {


                            $padraoFormatoData = date('d/m/Y', strtotime($row_Frequencia[2]));


                            echo("\t\t<td align='center' style:'padding:15px;'>&nbsp;" . $padraoFormatoData . "&nbsp;</td>\n");

                            echo("\t\t<td align='center' style:'padding:15px;'>&nbsp;" . $row_Frequencia[3] . "&nbsp;</td>\n");

                            echo("\t\t<td align='center' style:'padding:15px;'>&nbsp;" . $row_Frequencia[7] . "&nbsp;</td>\n");

                            echo("\t\t<td align='center' style:'padding:15px;'>&nbsp;" . $row_Frequencia[5] . "&nbsp;</td>\n");


                            $percentualPortas = $row_Frequencia[6] * 100;

                            $percentualPortasFormatado = round($percentualPortas, 2);


                            echo("\t\t<td align='center' style:'padding:15px;'>&nbsp;" . $percentualPortasFormatado . " %&nbsp;</td>\n");

                            echo("\t\t<td align='center' style:'padding:15px;'>&nbsp;" . $row_Frequencia[4] . "&nbsp;</td>\n");

                        }

                        $row_Frequencia = mysqli_fetch_row($resultFrequencia);

                        echo("\t</tr>\n");

                    }

                    echo("</table>");


                    echo "<br><br>";

                    echo "<strong>Tipo de presença:</strong> 1 - Normal / 2 - Manual / 3 - Videoconferência / 4 - Requerimento";

                    echo "<br><br>";


                }

                }
            }


       }

?>

<br />

    </div>

    </div>

<div id="footer" style="margin-bottom:0px;">
    <br />
    <div align="center">
        <div align="center"><span class="style22"><strong>ESCOLA DA MAGISTRATURA DO ESTADO   DO RIO DE JANEIRO - EMERJ<br />
	    Rua Dom Manuel, n&ordm; 25 - Centro - Telefone:   3133-1880<br /></strong></span>
        </div>
    </div>
</div>

<br><br>


<!--BOTÃO IMPRESSORA -->

<div id="btn-print"  align="center"><a href="javascript:print();"><img src="../images/impressora.png" width="36px" height="35px" /></a></div>



<br />

<br />

<script>


    //FUNÇÃO PARA SELECIONA INPUT TEXT DE ACORDO COM INPUT RADIO ESCOLHIDO

    function PegarValor2() {

        if(document.getElementById('valor').checked == true){

            document.getElementById('codInscricao').select();

            document.getElementById('codInscricao').disabled = false;

            document.getElementById('txtMascaraCpf').disabled = true;

            document.getElementById('buscar').style.display = "none";

            document.getElementById('txtMascaraCpf').style.display = "none";

            document.getElementById('campos_cpf').style.display = "none";



        }

        else if(document.getElementById('valor2').checked == true){

            document.getElementById('txtMascaraCpf').select();

            document.getElementById('txtMascaraCpf').disabled = false;

            document.getElementById('codInscricao').disabled = true;

            document.getElementById('buscar').style.display = "block";

            document.getElementById('txtMascaraCpf').style.display = "block";

            document.getElementById('campos_cpf').style.display = "block";

        }

    }

</script>

</body>

</html>