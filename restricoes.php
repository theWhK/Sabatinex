<?php session_start(); ?>
<body>
<?php
require_once './constants.php';
require('view/template.php');
$tela = new template;
$tela->SetTitle('Pesquisa operacional, simplificada - Sabatinex');
$tela->SetProjectName('Sabatinex');

//é criado dinimicamente os campos para preennche-los
if (isset($_GET['qtdevariaveis']) and isset($_GET['qtderestricoes']) and isset($_GET['objetivo']))
{
	$_SESSION['qtdevariaveis'] = $_GET['qtdevariaveis'];
	$_SESSION['qtderestricoes'] = $_GET['qtderestricoes'];
	$_SESSION['objetivo'] = $_GET['objetivo'];
	$_SESSION['qtdRest'] = $_GET['qtdRest'];
}

$conteudo=$conteudo.'<div class="row"><div class="col-md-2"></div>';
$conteudo=$conteudo.'
    <div class="col-md-8">
    <div >
        <h1>Programação Linear</h1>
        <hr>
    </div>
    ';

$conteudo=$conteudo.'<strong>Função Objetiva</strong><br>';

//Monta Z = .......
$conteudo=$conteudo.'<form name="frmdefinicao" method="GET" action="formapadrao.php">';
if ($_SESSION['objetivo']=='max')
{
	$conteudo=$conteudo.'<strong>Maximizar  Z =  </strong>';
}else{
	$conteudo=$conteudo.'<strong>Minimizar  Z =  </strong>';
}

for ($i=1; $i <= $_SESSION['qtdevariaveis']; $i++)
{ 
	$conteudo=$conteudo.'<span class="wlices--campo-valor--prepend"></span><input class="wlices--campo-valor" type="number" step="0.001" name="z'.$i.'" size="3" maxlength="4"> X<sub>'.$i.'</sub>';
	if($i<$_SESSION['qtdevariaveis'])
	{
		$conteudo=$conteudo.' + ';
	}
}
$conteudo=$conteudo.'<br><br>';

//Monta requisicoes
$conteudo=$conteudo.'<br><br><strong>Restrições</strong><br>';

//L de linha   C de coluna
for ($l=1; $l <= $_SESSION['qtderestricoes']; $l++)
{ 
	$conteudo=$conteudo.'<div class="wlices--restricao-wrapper">';
	for ($c=1; $c <= $_SESSION['qtdevariaveis'] ; $c++)
	{ 
		$conteudo=$conteudo.'<span class="wlices--campo-valor--prepend"></span><input class="wlices--campo-valor" type="number" step="0.001" name="r'.$l.'_'.$c.'" size="3" maxlength="4"> X<sub>'.$c.'</sub>';
		if($c<$_SESSION['qtdevariaveis'])
		{ 
			// se nao for a ultima coloca um ponto
	  	    $conteudo=$conteudo.' + ';
		}else{
			//se não acrescenta o resultado da linha
			$conteudo=$conteudo.'
				<!--<select id="relacao" name="relacao'.$l.'">
           			<option value= "<="> <=	</option>
				</select> --><span>   <=   </span>
        		<span class="wlices--campo-valor--prepend"></span><input class="wlices--campo-valor" type="number" step"0.001" name="resultado'.$l.'" size="3" maxlength="4">
			';
		}
	}
	$conteudo=$conteudo.'</div><br>';
}
$conteudo=$conteudo.'<br><br>';//espaçamento

//x1...>=0
$funcao='';
for ($c=1; $c <=$_SESSION['qtdevariaveis']; $c++)
{ 
	$funcao=$funcao.'X<sub>'.$c.'</sub>';
	if($c<$_SESSION['qtdevariaveis'] )
	{
		$funcao=$funcao.' , ';	
	}else{
		$funcao=$funcao.' >= 0;';
	}
}

$_SESSION['restricaopadrao']=$funcao;
$conteudo=$conteudo.$funcao;
$conteudo=$conteudo.'<br><br>';
$conteudo=$conteudo.'<button  type="button" style="margin-right:10px;color: #ffffff; background-color: #aaaaaa; border-color: #aaaaaa;" id="voltar" name="voltar" class="btn" onclick="window.location.href=\'/inicio_simplex.php \'">Voltar</button>';
$conteudo=$conteudo.'<button style="margin-right:10px" id="submit" name="submitDireto" class="btn btn-primary">Solução direta</button>';
$conteudo=$conteudo.'<button id="submit" name="submitDetalhado" class="btn btn-primary">Ver passo a passo</button>';
$conteudo=$conteudo.'</form>';
$conteudo=$conteudo.'</div></div>';

$sessionValuesArray = [];
for ( $i = 1; $i <= $_SESSION['qtdevariaveis']; $i++) {
	$sessionValuesArray['z'.$i] = $_SESSION['z'.$i];
}

for ($j = 1; $j <= $_SESSION['qtderestricoes']; $j++) {
	for ( $i = 1; $i <= $_SESSION['qtdevariaveis']; $i++) {
		$sessionValuesArray['r'.$j.'_'.$i] = $_SESSION['r'.$j.'_'.$i];

	}
	$sessionValuesArray['resultado'.$j] = $_SESSION['resultado'.$j];
}


$conteudo = $conteudo .
	'
<script>
window.onload = function() {
    var item = {
        objetivo: "'.$_SESSION['objetivo'].'",
        qtdevariaveis: "'.$_SESSION['qtdevariaveis'].'",
        qtderestricoes: "'.$_SESSION['qtderestricoes'].'",
        qtdRest: "'.$_SESSION['qtdRest'].'"
    };
	
	item = JSON.stringify(item);

    localStorage.setItem(\'inicio_simplex\', item);
	
	if ("'.$_GET['fix_values'].'" == "yes") {
    	var qtdVar = '.$_SESSION['qtdevariaveis'].',
    	    qtdRest = '.$_SESSION['qtderestricoes'].',
    	    sessionValues = '.json_encode($sessionValuesArray).';
    	    console.log(sessionValues);
    	
    	for (var i=1; i <= qtdVar; i++) {
    		$("input[name$=z"+ i +"]").val(sessionValues["z"+i]);
    	}
    	
    	for (var j=1; j <= qtdRest; j++) {
    		for (var i=1; i <= qtdVar; i++) {
    		    $("input[name$=r"+ j +"_"+ i +"]").val(sessionValues["r"+ j + "_" + i]);
    	    }
            $("input[name$=resultado"+ j +"]").val(sessionValues["resultado"+j]);
        }
	}
    
}
</script>
';

$tela->SetContent($conteudo);
$tela->ShowTemplate();
?>
