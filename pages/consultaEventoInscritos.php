<?php
    $vet = null;
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

header("Content-Type: text/html; charset=uft8", true);


if (isset($_POST["codEvento"])) {
	
	$query_rsBusca = "SELECT p.nome AS Nome, 
							 p.matriculatj AS Matricula, 
							 p.codigo AS Codigo 
					  FROM participante p, 
							inscricoes i, 
							evento e 
					  WHERE codevento=" . $_POST["codEvento"] . " AND
					  		p.codigo=i.codparticipante 	AND 
							e.codigo=" . $_POST["codEvento"]  .
					  " ORDER BY p.nome";
	$rsBusca = mysqli_query($conn, $query_rsBusca) or die(mysqli_error($conn));	
}

if (isset($_POST["codEvento"])) {
$vet[2] = implode("-",array_reverse(explode("/",$vet[2])));
	
	$query_rsBuscaNome = "SELECT distinct e.nome, e.codigo, po.data FROM evento e, porta po
					      WHERE e.codigo=" . $_POST["codEvento"] . " and po.codevento=e.codigo";
	$rsBuscaNome = mysqli_query($conn, $query_rsBuscaNome) or die(mysqli_error($conn));	
}
?>
    <!DOCTYPE html>
<html lang="pt-BR">
    <head>
        
        <link rel="stylesheet" href="../css/cadastrar_eventos.css">
        <link href="https://fonts.googleapis.com/css?family=Bree+Serif&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <meta http-equiv="Content-Type" content="text/html; charset=utf8">
        <style>
        
            #prox{
                color: red;
                font-size: 1.5em;
                font-family: cursive;
            }
            
            #border{
                 border-bottom: 1px solid teal
            }
        
        </style>
    </head>
    
    <body>
    
        <div id="title"><center><spam>LISTA COMPLETA PARTICIPANTES INSCRITOS COM E-MAIL</spam></center></div>
        
        <form id="cadastrar_eventos" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
            <div class="container">
                <fieldset>
                    <legend>Informe os dados abaixo:</legend>
  <div class="row">
      <div class="form-group">
          <div class="col-md-4 col-xs-9">
              <label for="codevento">Código do Evento:</label>
              <input class="form-control" type="text" id="codEvento" name="codEvento" placeholder="Código do evento">
          </div>
          <div class="col-md-2 col-xs-4">
              <input style="margin-top: 25px;" class="form-control btn btn-primary" type="submit" id="buscar" name="buscar" value="Buscar">
          </div>
      </div>
  </div>                 
    
        <br><br>
                     
     <div id="border"></div>  
                    
        <br><br>            
                    
    <?php if (isset($_POST["codEvento"])) { ?>

		<table width="850px" border="1" align="center" class="table table-striped">
			           
            <tr class="TDdata">
<?php  while ($vet = mysqli_fetch_row($rsBuscaNome)) { 
					$vet[2] = implode("/",array_reverse(explode("-",$vet[2])));
					echo "<tr style='background-color:#337AB7; color:white;' class='TDtable1'>";
					echo "<td colspan='5' class='TDtable1' align='center'>$vet[1] - $vet[0]</td>";
					echo "<td colspan='1' class='TDtable1' align='center'>Data: $vet[2]</td>";
					echo "</tr>";
					}
			?>
			</tr>			         
			<tr class="bg-primary">
				<td width="10px">Num.</td>
                <td width="390px">Nome</td>
				<td width="60px">Mat. TJ</td>
				<td width="60px" align='center'>Código</td>
				<td width="100px" align='center'>N. de Inscrição</td>
				<td width="290px">Assinatura</td>
			</tr>
<?php 			
			 $i = 1;
				while ($vet = mysqli_fetch_row($rsBusca)) { 
				
					//código de inscrição de participante
					$codEvento = $_POST["codEvento"];
					$codParticipante = $vet[2];
					//$codInscricao = $codEvento . $codParticipante;
					$codInscricao = sprintf("%06d%08d", $codEvento, $codParticipante);
			
					echo "<tr class='TDtable1'>";
					echo "<td class='TDtable1' align='center'>" . $i . "</td>"; 
					echo "<td class='TDtable1' align='left'><span style='text-transform:uppercase;'>$vet[0]</span></td>";
					echo "<td class='TDtable1' align='center'>$vet[1]&nbsp;</td>";
					echo "<td class='TDtable1' align='center'>$vet[2]</td>";
					echo "<td class='TDtable1' align='center'>$codInscricao</td>";
					echo "<td class='TDtable1' height='26px'>&nbsp;</td>"; 
					echo "</tr>"; 
					$i++;
				}
			?>
		</table>
		<br />
<?php
	} else {
		echo("<b><p>Nenhum evento foi selecionado!</p></b>");
		
	} ?>                
            </div>
          </div>  
        </div>

        
                    
</div>            
                 
 </fieldset>
        </form>
          
         <div id="footer" style="margin-bottom:0px;">
<br />
    <div align="center">
	  <div align="center"><span class="style22"><strong>ESCOLA DA MAGISTRATURA DO ESTADO   DO RIO DE JANEIRO - EMERJ<br />
	    Rua Dom Manuel, n&ordm; 25 - Centro - Telefone:   3133-1880<br />
      </strong></span></div>
  </div>
</div> 

    </body>
</html>